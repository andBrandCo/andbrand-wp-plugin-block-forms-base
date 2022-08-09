<?php

/**
 * Template for the Sender Email Block view.
 *
 * @package SebFormsWpPlugin
 */

use SebFormsWpPluginVendor\EightshiftLibs\Helpers\Components;

$unique = Components::getUnique();

$inputName = $attributes['senderEmailInputName'] ?? '';

echo Components::render(
	'input',
	Components::props('input', $attributes)
);
