import React from 'react';
import { __ } from '@wordpress/i18n';
import { TextControl, PanelBody, Button } from '@wordpress/components';
import {
	checkAttr,
	getAttrKey,
	props,
	icons,
	IconLabel,
	FancyDivider
} from '@eightshift/frontend-libs/scripts';
import { FieldOptions } from '../../field/components/field-options';
import { FieldOptionsAdvanced } from '../../field/components/field-options-advanced';
import manifest from '../manifest.json';

export const RadiosOptions = (attributes) => {
	const {
		setAttributes,
	} = attributes;

	const radiosName = checkAttr('radiosName', attributes, manifest);
	const radiosIsRequired = checkAttr('radiosIsRequired', attributes, manifest);

	return (
		<>
			<PanelBody title={__('Radio buttons', 'andbrand-block-forms-base')}>
				<FieldOptions
					{...props('field', attributes)}
				/>

				<FancyDivider label={__('Validation', 'andbrand-block-forms-base')} />

				<Button
					icon={icons.fieldRequired}
					isPressed={radiosIsRequired}
					onClick={() => setAttributes({ [getAttrKey('radiosIsRequired', attributes, manifest)]: !radiosIsRequired })}
				>
					{__('Required', 'andbrand-block-forms-base')}
				</Button>

				<FancyDivider label={__('Advanced', 'andbrand-block-forms-base')} />

				<TextControl
					label={<IconLabel icon={icons.fieldName} label={__('Name', 'andbrand-block-forms-base')} />}
					help={__('Should be unique! Used to identify the field within form submission data. If not set, a random name will be generated.', 'andbrand-block-forms-base')}
					value={radiosName}
					onChange={(value) => setAttributes({ [getAttrKey('radiosName', attributes, manifest)]: value })}
				/>

			</PanelBody>

			<FieldOptionsAdvanced
				{...props('field', attributes)}
			/>
		</>
	);
};
