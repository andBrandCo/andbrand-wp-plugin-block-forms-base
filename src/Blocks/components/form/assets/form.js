export class Form {
	constructor(options) {
		this.formSubmitRestApiUrl = options.formSubmitRestApiUrl;

		this.formSelector = options.formSelector;
		this.errorSelector = `${this.formSelector}-error`;
		this.loaderSelector = `${this.formSelector}-loader`;
		this.globalMsgSelector = `${this.formSelector}-global-msg`;

		this.CLASS_LOADER_ACTIVE = 'is-active';
	}

	init = () => {
		const elements = document.querySelectorAll(this.formSelector);

		[...elements].forEach((element) => {
			element.addEventListener('submit', this.onFormSubmit);
		});
	}

	onFormSubmit = (event) => {
		event.preventDefault();

		const element = event.target;

		this.showLoader(element);

		this.reset(element);

		const body = {
			method: element.getAttribute('method'),
			mode: 'same-origin',
			headers: {
				Accept: 'multipart/form-data',
			},
			body: this.formatFormData(element),
			credentials: 'same-origin',
			redirect: 'follow',
			referrer: 'no-referrer',
		};

		fetch(this.formSubmitRestApiUrl, body)
		.then((response) => {
			return response.json();
		})
		.then((response) => {

			this.hideLoader(element);

			if (response.code === 200) {

				// Send GTM.
				this.gtmSubmit(element);

				// If success, redirect or output msg.
				const isRedirect = element.getAttribute('data-success-redirect');
				if (isRedirect !== '') {
					window.location.href = isRedirect;
				} else {
					this.setGlobalMsg(element, response.message, 'success');
				}
			}

			// Normal errors.
			if (response.status === 'error') {
				this.setGlobalMsg(element, response.message, 'error');
			}

			// Fatal errors, trigger bugsnag.
			if (response.status === 'error_fatal') {
				this.setGlobalMsg(element, response.message, 'error');
				throw new Error(JSON.stringify(response));
			}

			// Validate fields.
			if (response.status === 'error_validation') {
				this.outputErrors(element, response.validation);
			}
		});
	}

	formatFormData = (element) => {
		const items = element.querySelectorAll('input, select, button, textarea');

		const formData = new FormData();

		for (const [key, item] of Object.entries(items)) {

			const {
				type,
				name,
				id,
				dataset = {},
				checked,
				files,
			} = item;

			let {
				value,
			} = item;

			let label = element.querySelector(`label[for="${id}"]`);

			if (label !== null) {
				label = label.innerText;
			} else {
				label = name;
			}

			if (type === 'checkbox' || type === 'radio') {
				if (!checked) {
					value = '';
				}
			}

			// Check for radio values.
			if (type === 'radio' && formData.get(name)) {
				if (!checked) {
					continue;
				} else {
					formData.delete(name);
				}
			}

			const data = {
				id,
				name,
				value,
				type,
				label,
				checked,
				data: dataset,
			};

			// Output all fields.
			if (type === 'file' && files.length) {

				for (const [key, file] of Object.entries(files)) {
					formData.append(`${name}-${key}`, file);
					data.value = "1";
					formData.append(`${name}-${key}`, JSON.stringify(data));
				}
			} else {
				formData.append(name, JSON.stringify(data));
			}
		}

		formData.append('es-form-post-id', JSON.stringify({
			id: 'es-form-post-id',
			value: element.getAttribute('data-form-post-id'),
			type: 'hidden',
			label: 'Form Id',
			data: {},
		}));

		return formData;
	}

	outputErrors = (element, fields) => {
		for (const [key] of Object.entries(fields)) {
			const item = element.querySelector(`${this.errorSelector}[data-id="${key}"]`);

			if (item !== null) {
				item.innerHTML = fields[key];
			}
		}
	}

	reset = (element) => {
		const items = element.querySelectorAll(this.errorSelector);
		[...items].forEach((item) => {
			item.innerHTML = '';
		});

		this.unsetGlobalMsg(element);
	}

	showLoader = (element) => {
		const item = element.querySelector(this.loaderSelector);
		if (item !== null) {
			item.classList.add(this.CLASS_LOADER_ACTIVE)
		}
	}

	hideLoader = (element) => {
		const item = element.querySelector(this.loaderSelector);

		if (item !== null) {
			item.classList.remove(this.CLASS_LOADER_ACTIVE)
		}
	}

	setGlobalMsg = (element, msg, status) => {
		const item = element.querySelector(this.globalMsgSelector);

		if (item !== null) {
			item.setAttribute('data-status', status);
			item.innerHTML = msg;
		}
	}

	unsetGlobalMsg(element) {
		const item = element.querySelector(this.globalMsgSelector);

		if (item !== null) {
			item.setAttribute('data-status', '');
			item.innerHTML = '';
		}
	}

	gtmSubmit(element) {
		const eventName = element.getAttribute('data-tracking-event-name');

		if (eventName) {
			const gtmData = this.getGtmData(element, eventName);

			if (window.dataLayer && gtmData?.event) {
				window.dataLayer.push(gtmData);
			}
		}
	}

	getGtmData(element, eventName) {
		const items = element.querySelectorAll('[data-tracking]');
		const data = {};

		if (!items.length) {
			return {};
		}

		[...items].forEach((item) => {
			const tracking = item.getAttribute('data-tracking');

			if (tracking) {
				const value = item.value;
				data[tracking] = value
			}
		});

		return Object.assign({}, { event: eventName, ...data });
	}
}