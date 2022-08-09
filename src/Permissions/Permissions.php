<?php

/**
 * File containing an user Permissions class.
 *
 * @package SebFormsWpPlugin\Permissions
 */

declare(strict_types=1);

namespace SebFormsWpPlugin\Permissions;

use SebFormsWpPlugin\AdminMenus\FormAdminMenu;
use SebFormsWpPlugin\AdminMenus\FormGlobalSettingsAdminSubMenu;
use SebFormsWpPlugin\AdminMenus\FormListingAdminSubMenu;
use SebFormsWpPlugin\AdminMenus\FormSettingsAdminSubMenu;
use SebFormsWpPlugin\CustomPostType\Forms;

use SebFormsWpPlugin\Helpers\Helper;

/**
 * Class Permissions
 */
class Permissions
{
	/**
	 * Default user role to assign permissions.
	 */
	public const DEFAULT_MINIMAL_ROLES = [
		'editor',
		'administrator',
	];

	/**
	 * All permissions.
	 *
	 * @return array <string>
	 */
	public static function getPermissions(): array
	{
		$postType = Forms::POST_CAPABILITY_TYPE;

		Helper::logger([
				'Class' => 'Permissions',
				'Method' => 'getPermissions',
				'formIdUsed' => $postType,
			]);

		return [
			$postType,
			FormAdminMenu::ADMIN_MENU_CAPABILITY,
			FormGlobalSettingsAdminSubMenu::ADMIN_MENU_CAPABILITY,
			FormListingAdminSubMenu::ADMIN_MENU_CAPABILITY,
			FormSettingsAdminSubMenu::ADMIN_MENU_CAPABILITY,
			"edit_{$postType}",
			"read_{$postType}",
			"delete_{$postType}",
			"edit_{$postType}s",
			"edit_others_{$postType}s",
			"delete_{$postType}s",
			"publish_{$postType}s",
			"read_private_{$postType}s",
		];
	}
}
