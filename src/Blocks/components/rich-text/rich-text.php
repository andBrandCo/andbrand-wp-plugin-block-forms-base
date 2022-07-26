<?php

/**
 * Template for the RichText Component.
 *
 * @package AndbrandWpPluginBlockFormsBase
 */

use AndbrandWpPluginBlockFormsBaseVendor\EightshiftLibs\Helpers\Components;

$manifest = Components::getManifest(__DIR__);

$richTextContent = Components::checkAttr('richTextContent', $attributes, $manifest);
$richTextId = Components::checkAttr('richTextId', $attributes, $manifest);
$richTextName = Components::checkAttr('richTextName', $attributes, $manifest);

echo Components::render(
	'field',
	array_merge(
		Components::props('field', $attributes, [
			'fieldContent' => $richTextContent,
			'fieldId' => $richTextId,
			'fieldName' => $richTextName,
			'fieldHideLabel' => true,
			'fieldUseError' => false,
		]),
		[
			'additionalFieldClass' => $attributes['additionalFieldClass'] ?? '',
			'selectorClass' => $manifest['componentName'] ?? '',
		]
	)
);
