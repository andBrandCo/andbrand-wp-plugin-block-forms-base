<?php

/**
 * Template for the Form Selector Block view.
 *
 * @package SebFormsWpPlugin
 */

use SebFormsWpPlugin\Hooks\Filters;

// Add custom additional content filter.
$filterName = Filters::getBlockFilterName('formSelector', 'additionalContent');
if (has_filter($filterName)) {
	echo apply_filters($filterName, ''); // phpcs:ignore Eightshift.Security.ComponentsEscape.OutputNotEscaped
}

echo $innerBlockContent; // phpcs:ignore Eightshift.Security.ComponentsEscape.OutputNotEscaped
