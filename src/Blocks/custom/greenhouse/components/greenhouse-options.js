/* global esFormsBlocksLocalization */

import React from 'react';
import { __ } from '@wordpress/i18n';
import { select } from "@wordpress/data";
import { PanelBody, BaseControl, Button } from '@wordpress/components';
import { IconLabel, icons, STORE_NAME } from '@eightshift/frontend-libs/scripts';

export const GreenhouseOptions = ({ postId }) => {
	const {
		settingsPageUrl,
	} = select(STORE_NAME).getSettings();

	const wpAdminUrl = esFormsBlocksLocalization.wpAdminUrl;

	return (
		<PanelBody title={__('Greenhouse', 'seb-forms')}>
			<BaseControl
				label={<IconLabel icon={icons.options} label={__('Settings', 'seb-forms')} />}
				help={__('On Greenhouse settings page you can setup all details regarding you integration.', 'seb-forms')}
			>
				<Button
					href={`${wpAdminUrl}${settingsPageUrl}&formId=${postId}&type=greenhouse`}
					isSecondary
				>
					{__('Open Greenhouse Form Settings', 'seb-forms')}
				</Button>
			</BaseControl>
		</PanelBody>
	);
};
