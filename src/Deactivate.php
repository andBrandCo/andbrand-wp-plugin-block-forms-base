<?php

/**
 * The file that defines actions on plugin deactivation.
 *
 * @package SebFormsWpPlugin
 */

declare(strict_types=1);

namespace SebFormsWpPlugin;

use SebFormsWpPlugin\Cache\SettingsCache;
use SebFormsWpPlugin\Permissions\Permissions;
use SebFormsWpPluginVendor\EightshiftLibs\Plugin\HasDeactivationInterface;
use WP_Role;

/**
 * The plugin deactivation class.
 */
class Deactivate implements HasDeactivationInterface
{
	/**
	 * Deactivate the plugin.
	 */
	public function deactivate(): void
	{
		// Remove caps.
		foreach (Permissions::DEFAULT_MINIMAL_ROLES as $roleName) {
			$role = \get_role($roleName);

			if ($role instanceof WP_Role) {
				foreach (Permissions::getPermissions() as $item) {
					$role->remove_cap($item);
				}
			}
		}

		// Delet transients.
		foreach (SettingsCache::ALL_CACHE as $items) {
			foreach ($items as $item) {
				\delete_transient($item);
			}
		}

		// Do a cleanup.
		\flush_rewrite_rules();
	}
}
