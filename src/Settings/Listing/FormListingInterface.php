<?php

/**
 * Class that holds data for admin forms listing.
 *
 * @package SebFormsWpPlugin\Settings\Listing
 */

declare(strict_types=1);

namespace SebFormsWpPlugin\Settings\Listing;

/**
 * Interface for admin content listing
 */
interface FormListingInterface
{
	/**
	 * Get Forms List.
	 *
	 * @param string $status Status for listing to output.
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function getFormsList(string $status): array;
}
