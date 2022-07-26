<?php

/**
 * Template for the Textarea Block view.
 *
 * @package AndbrandWpPluginBlockFormsBase
 */

use AndbrandWpPluginBlockFormsBaseVendor\EightshiftLibs\Helpers\Components;

$unique = Components::getUnique();

$textareaName = $attributes['textareaTextareaName'] ?? '';
$textareaId = $attributes['textareaTextareaId'] ?? '';
$props = [];

if (empty($textareaName)) {
	$props['textareaName'] = $textareaId;
}

echo Components::render(
	'textarea',
	Components::props('textarea', $attributes, $props)
);
