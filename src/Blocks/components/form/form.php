<?php

/**
 * Template for the Form Component.
 *
 * @package EightshiftForms
 */

use EightshiftForms\Helpers\Components;

$manifest = Components::getManifest(__DIR__);

$componentClass = $manifest['componentClass'] ?? '';
$additionalClass = $attributes['additionalClass'] ?? '';
$blockClass = $attributes['blockClass'] ?? '';
$selectorClass = $attributes['selectorClass'] ?? $componentClass;

$formName = Components::checkAttr('formName', $attributes, $manifest);
$formAction = Components::checkAttr('formAction', $attributes, $manifest);
$formMethod = Components::checkAttr('formMethod', $attributes, $manifest);
$formTarget = Components::checkAttr('formTarget', $attributes, $manifest);
$formId = Components::checkAttr('formId', $attributes, $manifest);
$formAllowedBlocks = Components::checkAttr('formAllowedBlocks', $attributes, $manifest);

$formClass = Components::classnames([
	Components::selector($componentClass, $componentClass),
	Components::selector($blockClass, $blockClass, $selectorClass),
	Components::selector($additionalClass, $additionalClass),
]);

?>

<form
	class="<?php echo esc_attr($formClass); ?>"
	name="<?php echo esc_attr($formName); ?>"
	id="<?php echo esc_attr($formId); ?>"
	action="<?php echo esc_attr($formAction); ?>"
	method="<?php echo esc_attr($formMethod); ?>"
	target="<?php echo esc_attr($formTarget); ?>"
>
</form>
