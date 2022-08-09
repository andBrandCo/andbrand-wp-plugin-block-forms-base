/* global esFormsBlocksLocalization */

import React, { useState } from 'react';
import { __ } from '@wordpress/i18n';
import { isArray } from 'lodash';
import { TextControl, PanelBody, Button, Popover } from '@wordpress/components';
import {
	icons,
	checkAttr,
	getAttrKey,
	IconLabel,
	props,
	SimpleVerticalSingleSelect,
	FancyDivider,
} from '@eightshift/frontend-libs/scripts';
import { FieldOptions } from '../../../components/field/components/field-options';
import { FieldOptionsAdvanced } from '../../field/components/field-options-advanced';
import manifest from '../manifest.json';

export const TextareaOptions = (attributes) => {
	const {
		setAttributes,
	} = attributes;

	const textareaName = checkAttr('textareaName', attributes, manifest);
	const textareaValue = checkAttr('textareaValue', attributes, manifest);
	const textareaPlaceholder = checkAttr('textareaPlaceholder', attributes, manifest);
	const textareaIsDisabled = checkAttr('textareaIsDisabled', attributes, manifest);
	const textareaIsReadOnly = checkAttr('textareaIsReadOnly', attributes, manifest);
	const textareaIsRequired = checkAttr('textareaIsRequired', attributes, manifest);
	const textareaTracking = checkAttr('textareaTracking', attributes, manifest);
	const textareaValidationPattern = checkAttr('textareaValidationPattern', attributes, manifest);

	let textareaValidationPatternOptions = [];

	if (typeof esFormsBlocksLocalization !== 'undefined' && isArray(esFormsBlocksLocalization?.validationPatternsOptions)) {
		textareaValidationPatternOptions = esFormsBlocksLocalization.validationPatternsOptions;
	}

	const [showValidation, setShowValidation] = useState(false);

	return (
		<>
			<PanelBody title={__('Multiline text', 'seb-forms')}>
				<FieldOptions
					{...props('field', attributes)}
				/>

				<TextControl
					label={<IconLabel icon={icons.fieldPlaceholder} label={__('Placeholder', 'seb-forms')} />}
					help={__('Shown when the field is empty', 'seb-forms')}
					value={textareaPlaceholder}
					onChange={(value) => setAttributes({ [getAttrKey('textareaPlaceholder', attributes, manifest)]: value })}
				/>

				<FancyDivider label={__('Validation', 'seb-forms')} />

				<div className='es-h-spaced'>
					<Button
						icon={icons.fieldRequired}
						isPressed={textareaIsRequired}
						onClick={() => setAttributes({ [getAttrKey('textareaIsRequired', attributes, manifest)]: !textareaIsRequired })}
					>
						{__('Required', 'seb-forms')}
					</Button>

					<Button
						icon={icons.regex}
						isPressed={textareaValidationPattern?.length > 0}
						onClick={() => setShowValidation(true)}
					>
						{__('Pattern validation', 'seb-forms')}

						{showValidation &&
							<Popover noArrow={false} onClose={() => setShowValidation(false)}>
								<div className='es-popover-content'>
									<SimpleVerticalSingleSelect
										label={__('Validation pattern', 'seb-forms')}
										options={textareaValidationPatternOptions.map(({ label, value }) => ({
											onClick: () => setAttributes({ [getAttrKey('textareaValidationPattern', attributes, manifest)]: value }),
											label: label,
											isActive: textareaValidationPattern === value,
										}))}
									/>
								</div>
							</Popover>
						}
					</Button>
				</div>

				<FancyDivider label={__('Advanced', 'seb-forms')} />

				<TextControl
					label={<IconLabel icon={icons.fieldValue} label={__('Initial value', 'seb-forms')} />}
					value={textareaValue}
					onChange={(value) => setAttributes({ [getAttrKey('textareaValue', attributes, manifest)]: value })}
				/>

				<TextControl
					label={<IconLabel icon={icons.fieldName} label={__('Name', 'seb-forms')} />}
					help={__('Should be unique! Used to identify the field within form submission data. If not set, a random name will be generated.', 'seb-forms')}
					value={textareaName}
					onChange={(value) => setAttributes({ [getAttrKey('textareaName', attributes, manifest)]: value })}
				/>

				<div className='es-h-spaced'>
					<Button
						icon={icons.fieldReadonly}
						isPressed={textareaIsReadOnly}
						onClick={() => setAttributes({ [getAttrKey('textareaIsReadOnly', attributes, manifest)]: !textareaIsReadOnly })}
					>
						{__('Read-only', 'seb-forms')}
					</Button>

					<Button
						icon={icons.fieldDisabled}
						isPressed={textareaIsDisabled}
						onClick={() => setAttributes({ [getAttrKey('textareaIsDisabled', attributes, manifest)]: !textareaIsDisabled })}
					>
						{__('Disabled', 'seb-forms')}
					</Button>
				</div>

				<FancyDivider label={__('Tracking', 'seb-forms')} />

				<TextControl
					label={<IconLabel icon={icons.code} label={__('GTM tracking code', 'seb-forms')} />}
					value={textareaTracking}
					onChange={(value) => setAttributes({ [getAttrKey('textareaTracking', attributes, manifest)]: value })}
				/>
			</PanelBody>

			<FieldOptionsAdvanced
				{...props('field', attributes)}
			/>
		</>
	);
};
