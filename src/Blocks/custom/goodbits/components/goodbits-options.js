/* global esFormsBlocksLocalization */

import React from 'react';
import { __ } from '@wordpress/i18n';
import { select } from "@wordpress/data";
import { PanelBody, BaseControl, Button } from '@wordpress/components';
import { IconLabel, icons, STORE_NAME } from '@eightshift/frontend-libs/scripts';

export const GoodbitsOptions = ({ postId }) => {
	const {
		settingsPageUrl,
	} = select(STORE_NAME).getSettings();

	const wpAdminUrl = esFormsBlocksLocalization.wpAdminUrl;
	
	return (
		<PanelBody title={__('Goodbits', 'seb-forms')}>
			<BaseControl
				label={<IconLabel icon={icons.options} label={__('Settings', 'seb-forms')} />}
				help={__('On Goodbits settings page you can setup all details regarding you integration.', 'seb-forms')}
			>
				<Button
					href={`${wpAdminUrl}${settingsPageUrl}&formId=${postId}&type=goodbits`}
					isSecondary
				>
					{__('Open Goodbits Form Settings', 'seb-forms')}
				</Button>
			</BaseControl>
		</PanelBody>
	);
};
