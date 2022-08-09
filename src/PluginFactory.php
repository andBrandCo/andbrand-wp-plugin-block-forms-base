<?php

/**
 * The file that defines a factory for activating / deactivating plugin.
 *
 * @package SebFormsWpPlugin
 */

declare(strict_types=1);

namespace SebFormsWpPlugin;

/**
 * The plugin factory class.
 */
class PluginFactory
{
	/**
	 * Activate the plugin.
	 */
	public static function activate(): void
	{
		(new Activate())->activate();
	}

	/**
	 * Deactivate the plugin.
	 */
	public static function deactivate(): void
	{
		(new Deactivate())->deactivate();
	}
}
