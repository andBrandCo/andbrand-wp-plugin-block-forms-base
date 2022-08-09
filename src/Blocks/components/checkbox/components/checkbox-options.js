import React from 'react';
import { __ } from '@wordpress/i18n';
import { TextControl, Button } from '@wordpress/components';
import {
	checkAttr,
	getAttrKey,
	icons,
	IconLabel,
	FancyDivider
} from '@eightshift/frontend-libs/scripts';
import manifest from '../manifest.json';

export const CheckboxOptions = (attributes) => {
	const {
		setAttributes,
	} = attributes;

	const checkboxLabel = checkAttr('checkboxLabel', attributes, manifest);
	const checkboxValue = checkAttr('checkboxValue', attributes, manifest);
	const checkboxIsChecked = checkAttr('checkboxIsChecked', attributes, manifest);
	const checkboxIsDisabled = checkAttr('checkboxIsDisabled', attributes, manifest);
	const checkboxIsReadOnly = checkAttr('checkboxIsReadOnly', attributes, manifest);
	const checkboxTracking = checkAttr('checkboxTracking', attributes, manifest);

	return (
		<>
			<TextControl
				label={<IconLabel icon={icons.textUppercase} label={__('Checkbox label', 'seb-forms')} />}
				value={checkboxLabel}
				onChange={(value) => setAttributes({ [getAttrKey('checkboxLabel', attributes, manifest)]: value })}
			/>

			<div className='es-h-spaced'>
				<Button
					icon={icons.checkSquare}
					isPressed={checkboxIsChecked}
					onClick={() => setAttributes({ [getAttrKey('checkboxIsChecked', attributes, manifest)]: !checkboxIsChecked })}
				>
					{__('Check by default', 'seb-forms')}
				</Button>
			</div>

			<FancyDivider label={__('Advanced', 'seb-forms')} />

			<TextControl
				label={<IconLabel icon={icons.fieldValue} label={__('Value', 'seb-forms')} />}
				help={__('Internal value, sent if checked.', 'seb-forms')}
				value={checkboxValue}
				onChange={(value) => setAttributes({ [getAttrKey('checkboxValue', attributes, manifest)]: value })}
			/>

			<div className='es-h-spaced'>
				<Button
					icon={icons.fieldReadonly}
					isPressed={checkboxIsReadOnly}
					onClick={() => setAttributes({ [getAttrKey('checkboxIsReadOnly', attributes, manifest)]: !checkboxIsReadOnly })}
				>
					{__('Read-only', 'seb-forms')}
				</Button>

				<Button
					icon={icons.fieldDisabled}
					isPressed={checkboxIsDisabled}
					onClick={() => setAttributes({ [getAttrKey('checkboxIsDisabled', attributes, manifest)]: !checkboxIsDisabled })}
				>
					{__('Disabled', 'seb-forms')}
				</Button>
			</div>

			<FancyDivider label={__('Tracking', 'seb-forms')} />

			<TextControl
				label={<IconLabel icon={icons.code} label={__('GTM tracking code', 'seb-forms')} />}
				value={checkboxTracking}
				onChange={(value) => setAttributes({ [getAttrKey('checkboxTracking', attributes, manifest)]: value })}
			/>
		</>
	);
};
