<?php

/**
 * Template for the Select Block view.
 *
 * @package SebFormsWpPlugin
 */

use SebFormsWpPluginVendor\EightshiftLibs\Helpers\Components;

$unique = Components::getUnique();

$selectName = $attributes['selectSelectName'] ?? '';
$selectId = $attributes['selectSelectId'] ?? '';
$props = [];

if (empty($selectName)) {
	$props['selectName'] = $selectId;
}

$props['selectOptions'] = $innerBlockContent;

echo Components::render(
	'select',
	Components::props('select', $attributes, $props)
);
