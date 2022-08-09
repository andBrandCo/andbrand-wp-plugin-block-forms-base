<?php

/**
 * Class that holds class for FormsPostType custom post type registration.
 *
 * @package SebFormsWpPlugin\CustomPostType
 */

declare(strict_types=1);

namespace SebFormsWpPlugin\CustomPostType;

use SebFormsWpPluginVendor\EightshiftLibs\CustomPostType\AbstractPostType;

/**
 * Class FormsPostType.
 */
class Forms extends AbstractPostType
{
	/**
	 * Post type slug constant.
	 *
	 * @var string
	 */
	public const POST_TYPE_SLUG = 'seb-block-forms';

	/**
	 * URL slug for the custom post type.
	 *
	 * @var string
	 */
	public const POST_TYPE_URL_SLUG = 'seb-block-forms';

	/**
	 * Rest API Endpoint slug constant.
	 *
	 * @var string
	 */
	public const REST_API_ENDPOINT_SLUG = 'seb-block-forms';

	/**
	 * Post type slug constant.
	 *
	 * @var string
	 */
	public const POST_CAPABILITY_TYPE = 'seb_block_forms';

	/**
	 * Browser url slug constant.
	 *
	 * @var string
	 */
	public const URL_SLUG = 'seb-block-forms';

	/**
	 * Location of menu in sidebar.
	 *
	 * @var int
	 */
	public const MENU_POSITION = 50;

	/**
	 * Set menu icon.
	 *
	 * @var string
	 */
	public const MENU_ICON = 'dashicons-media-document';

	/**
	 * Get the slug to use for the Projects custom post type.
	 *
	 * @return string Custom post type slug.
	 */
	protected function getPostTypeSlug(): string
	{
		return self::POST_TYPE_SLUG;
	}

	/**
	 * Get the arguments that configure the Projects custom post type.
	 *
	 * @return array<mixed> Array of arguments.
	 */
	protected function getPostTypeArguments(): array
	{
		$template = [
			[
				'seb-forms/form-selector', [], []
			]
		];

		return [
			'label' => \esc_html__('Seb FormsBlock Forms', 'seb-block-forms'),
			'public' => true,
			'menu_position' => static::MENU_POSITION,
			'menu_icon' => static::MENU_ICON,
			'supports' => ['title', 'editor', 'revision'],
			'has_archive' => false,
			'show_in_rest' => true,
			'publicly_queryable' => false,
			'show_in_menu' => false,
			'show_in_nav_menus' => false,
			'can_export' => true,
			'capability_type' => self::POST_CAPABILITY_TYPE,
			'rest_base' => static::REST_API_ENDPOINT_SLUG,
			'template_lock' => 'all',
			'template' => $template,
		];
	}
}
