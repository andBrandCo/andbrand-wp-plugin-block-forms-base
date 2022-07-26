<?php

/**
 * Template for the Form Block view.
 *
 * @package AndbrandWpPluginBlockFormsBase
 */

use AndbrandWpPluginBlockFormsBase\Form\Form;
use AndbrandWpPluginBlockFormsBaseVendor\EightshiftLibs\Helpers\Components;

$manifest = Components::getManifest(__DIR__);

$blockClass = $attributes['blockClass'] ?? '';

$formFormPostId = Components::checkAttr('formFormPostId', $attributes, $manifest);

// Check if mailer data is set and valid.
$formClass = Components::classnames([
	Components::selector($blockClass, $blockClass),
]);

?>

<div class="<?php echo esc_attr($formClass); ?>">
	<?php
	// There is no bailout here in case of missing settings because custom form can be used only to redirecto to another page with form data.
	echo Components::render(
		'form',
		Components::props('form', $attributes, array_merge(
			[
				'formContent' => $innerBlockContent,
			],
			apply_filters(
				Form::FILTER_FORM_SETTINGS_OPTIONS_NAME,
				$formFormPostId
			)
		))
	);
	?>
</div>
