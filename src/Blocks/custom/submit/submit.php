<?php

/**
 * Template for the Submit Block view.
 *
 * @package AndbrandWpPluginBlockFormsBase
 */

use AndbrandWpPluginBlockFormsBaseVendor\EightshiftLibs\Helpers\Components;

$unique = Components::getUnique();

$submitName = $attributes['submitSubmitName'] ?? '';
$submitId = $attributes['submitSubmitId'] ?? '';
$props = [];

echo Components::render(
	'submit',
	Components::props('submit', $attributes, $props)
);
