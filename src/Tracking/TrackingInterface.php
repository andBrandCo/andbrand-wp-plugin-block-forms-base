<?php

/**
 * Tracking data interface.
 *
 * @package SebFormsWpPlugin\Tracking
 */

declare(strict_types=1);

namespace SebFormsWpPlugin\Tracking;

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