<?php

/**
 * Interface that holds all methods for building single form settings form.
 *
 * @package SebFormsWpPlugin\Settings\Settings
 */

declare(strict_types=1);

namespace SebFormsWpPlugin\Settings\Settings;

/**
 * Interface for SettingsDataInterface
 */
interface SettingsDataInterface
{
	/**
	 * Get Settings sidebar data.
	 *
	 * @return array<string, mixed>
	 */
	public function getSettingsSidebar(): array;

	/**
	 * Get Form settings data array
	 *
	 * @param string $formId Form Id.
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function getSettingsData(string $formId): array;
}
