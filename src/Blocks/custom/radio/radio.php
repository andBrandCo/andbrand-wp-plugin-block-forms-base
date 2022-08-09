<?php

/**
 * Template for the radio item Block view.
 *
 * @package SebFormsWpPlugin
 */

use SebFormsWpPlugin\Blocks\Blocks;
use SebFormsWpPluginVendor\EightshiftLibs\Helpers\Components;

$unique = Components::getUnique();

$radioLabel = $attributes['radioRadioLabel'] ?? '';
$radioId = $attributes['radioRadioId'] ?? '';
$radioValue = $attributes['radioRadioValue'] ?? '';
$props = [];

if (empty($radioValue)) {
	$props['radioValue'] = apply_filters(Blocks::BLOCKS_STRING_TO_VALUE_FILTER_NAME, $radioLabel);
}

echo Components::render(
	'radio',
	Components::props('radio', $attributes, $props)
);
