<?php

/**
 * Interface that holds all methods for building single form settings form - Clearbit specific.
 *
 * @package AndbrandWpPluginBlockFormsBase\Integrations\Clearbit
 */

declare(strict_types=1);

namespace AndbrandWpPluginBlockFormsBase\Integrations\Clearbit;

use AndbrandWpPluginBlockFormsBase\Settings\Settings\SettingsDataInterface;

/**
 * Interface for SettingsClearbitDataInterface.
 */
interface SettingsClearbitDataInterface extends SettingsDataInterface
{
	/**
	 * Output array settings for form.
	 *
	 * @param string $formId Form ID.
	 * @param array<int, array<string, mixed>> $formFields Items from cache data.
	 * @param array<string, string> $keys Array of keys to get data from.
	 *
	 * @return array<int, array<string, array<int|string, array<string, mixed>>|bool|string>>
	 */
	public function getOutputClearbit(string $formId, array $formFields, array $keys): array;

	/**
	 * Output array settings for form - global.
	 *
	 * @param array<string, string> $properties Array of properties from integration.
	 * @param array<string, string> $keys Array of keys to get data from.
	 *
	 * @return array<int, array<string, array<int|string, array<string, mixed>>|bool|string>>
	 */
	public function getOutputGlobalClearbit(array $properties, array $keys): array;
}
