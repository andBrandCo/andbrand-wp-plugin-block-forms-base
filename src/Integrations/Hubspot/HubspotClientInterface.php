<?php

/**
 * File containing Hubspot specific interface.
 *
 * @package SebFormsWpPlugin\Integrations\Hubspot
 */

namespace SebFormsWpPlugin\Integrations\Hubspot;

use SebFormsWpPlugin\Integrations\ClientInterface;

/**
 * Interface for a Client
 */
interface HubspotClientInterface extends ClientInterface
{
	/**
	 * Return contact properties with cache option for faster loading.
	 *
	 * @return array<string, mixed>
	 */
	public function getContactProperties(): array;

	/**
	 * Post contact property to HubSpot.
	 *
	 * @param string $email Email to connect data to.
	 * @param array<string, mixed> $params Params array.
	 *
	 * @return array<string, mixed>
	 */
	public function postContactProperty(string $email, array $params): array;
}
