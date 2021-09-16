<?php

/**
 * File containing an abstract class for holding Assets Manifest functionality.
 *
 * It is used to provide manifest.json file location used with Webpack to fetch correct file locations.
 *
 * @package EightshiftForms\Manifest
 */

declare(strict_types=1);

namespace EightshiftForms\Manifest;

use EightshiftForms\Config\Config;
use EightshiftFormsPluginVendor\EightshiftLibs\Manifest\AbstractManifest;

/**
 * Class Manifest
 */
class Manifest extends AbstractManifest
{

	/**
	 * Manifest item filter name constant.
	 *
	 * @var string
	 */
	public const MANIFEST_ITEM = 'es-forms-manifest-item';

	/**
	 * Register all hooks. Changed filter name to manifest.
	 *
	 * @return void
	 */
	public function register(): void
	{
		\add_action('init', [$this, 'setAssetsManifestRaw']);
		\add_filter(static::MANIFEST_ITEM, [$this, 'getAssetsManifestItem']);
	}

	/**
	 * Manifest file path getter.
	 *
	 * @return string
	 */
	public function getManifestFilePath(): string
	{
		return Config::getProjectPath() . '/public/manifest.json';
	}
}
