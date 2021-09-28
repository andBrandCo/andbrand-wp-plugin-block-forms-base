<?php

/**
 * Template for the Form Block view.
 *
 * @package EightshiftForms
 */

use EightshiftForms\Helpers\Components;
use EightshiftForms\Helpers\Helper;
use EightshiftForms\Settings\Settings\SettingsAll;
use EightshiftForms\Settings\Settings\SettingsGeneral;

$manifest = Components::getManifest(__DIR__);

$blockClass = $attributes['blockClass'] ?? '';

$formPostId = Components::checkAttr('formPostId', $attributes, $manifest);
$formPostIdDecoded = Helper::encryptor('decode', $formPostId);

$formClass = Components::classnames([
	Components::selector($blockClass, $blockClass),
]);

?>

<div class="<?php echo esc_attr($formClass); ?>">
	<?php
	echo Components::render( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		'form',
		Components::props('form', $attributes, [
			'formContent' => $innerBlockContent,
			'formPostId' => $formPostId,
			'formTrackingEventName' => \apply_filters(
				SettingsAll::FILTER_BLOCK_SETTING_VALUE_NAME,
				SettingsGeneral::SETTINGS_GENERAL_TRACKING_EVENT_NAME_KEY,
				$formPostIdDecoded
			),
			'formSuccessRedirect' => \apply_filters(
				SettingsAll::FILTER_BLOCK_SETTING_VALUE_NAME,
				SettingsGeneral::SETTINGS_GENERAL_REDIRECTION_SUCCESS_KEY,
				$formPostIdDecoded
			),
		])
	);
	?>
</div>
