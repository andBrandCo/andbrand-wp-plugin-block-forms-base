import React from 'react';
import { __ } from '@wordpress/i18n';
import { TextControl, PanelBody, Button } from '@wordpress/components';
import {
	icons,
	checkAttr,
	getAttrKey,
	IconLabel,
	props,
	FancyDivider
} from '@eightshift/frontend-libs/scripts';
import { FieldOptions } from '../../../components/field/components/field-options';
import { FieldOptionsAdvanced } from '../../field/components/field-options-advanced';
import manifest from '../manifest.json';

export const SelectOptions = (attributes) => {
	const {
		setAttributes,
	} = attributes;

	const selectName = checkAttr('selectName', attributes, manifest);
	const selectIsDisabled = checkAttr('selectIsDisabled', attributes, manifest);
	const selectIsRequired = checkAttr('selectIsRequired', attributes, manifest);
	const selectTracking = checkAttr('selectTracking', attributes, manifest);

	return (
		<>
			<PanelBody title={__('Select', 'andbrand-block-forms-base')}>
				<FieldOptions
					{...props('field', attributes)}
				/>

				<FancyDivider label={__('Validation', 'andbrand-block-forms-base')} />

				<div className='es-h-spaced'>
					<Button
						icon={icons.fieldRequired}
						isPressed={selectIsRequired}
						onClick={() => setAttributes({ [getAttrKey('selectIsRequired', attributes, manifest)]: !selectIsRequired })}
					>
						{__('Required', 'andbrand-block-forms-base')}
					</Button>
				</div>

				<FancyDivider label={__('Advanced', 'andbrand-block-forms-base')} />

				<TextControl
					label={<IconLabel icon={icons.fieldName} label={__('Name', 'andbrand-block-forms-base')} />}
					help={__('Should be unique! Used to identify the field within form submission data. If not set, a random name will be generated.', 'andbrand-block-forms-base')}
					value={selectName}
					onChange={(value) => setAttributes({ [getAttrKey('selectName', attributes, manifest)]: value })}
				/>

				<div className='es-h-spaced'>
					<Button
						icon={icons.fieldDisabled}
						isPressed={selectIsDisabled}
						onClick={() => setAttributes({ [getAttrKey('selectIsDisabled', attributes, manifest)]: !selectIsDisabled })}
					>
						{__('Disabled', 'andbrand-block-forms-base')}
					</Button>
				</div>

				<FancyDivider label={__('Tracking', 'andbrand-block-forms-base')} />

				<TextControl
					label={<IconLabel icon={icons.code} label={__('GTM tracking code', 'andbrand-block-forms-base')} />}
					value={selectTracking}
					onChange={(value) => setAttributes({ [getAttrKey('selectTracking', attributes, manifest)]: value })}
				/>
			</PanelBody>

			<FieldOptionsAdvanced
				{...props('field', attributes)}
			/>
		</>
	);
};
