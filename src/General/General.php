<?php

/**
 * File containing an class for general configuration.
 *
 * @package SebFormsWpPlugin\General
 */

declare(strict_types=1);

namespace SebFormsWpPlugin\General;

use SebFormsWpPlugin\Hooks\Filters;
use SebFormsWpPluginVendor\EightshiftLibs\Services\ServiceInterface;

/**
 * Class General
 */
class General implements ServiceInterface
{
	/**
	 * Register all hooks.
	 *
	 * @return void
	 */
	public function register(): void
	{
		\add_filter('http_request_args', [$this, 'getHttpRequestArgs']);
	}

	/**
	 * Return http request args.
	 *
	 * @param array<int, mixed> $args Arguments from core.
	 *
	 * @return array<int, mixed>
	 */
	public function getHttpRequestArgs(array $args): array
	{
		$args['timeout'] = 30;

		return \apply_filters(Filters::getGeneralSettingsFilterName('httpRequestArgs'), $args); // phpcs:ignore WordPress.NamingConventions.ValidHookName.NotLowercase
	}
}
