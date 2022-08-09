<?php

/**
 * Tracking data interface.
 *
 * @package AndbrandWpPluginBlockFormsBase\Tracking
 */

declare(strict_types=1);

namespace AndbrandWpPluginBlockFormsBase\Tracking;

/**
 * Interface TrackingInterface
 */
interface TrackingInterface
{
	/**
	 * Return tracking expiration time in days.
	 *
	 * @return string
	 */
	public function getTrackingExpiration(): string;

	/**
	 * Return encripted data from get url param.
	 *
	 * @return string
	 */
	public function getTrackingToLocalStorage(): string;
}