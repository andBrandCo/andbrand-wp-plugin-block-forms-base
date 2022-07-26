<?php

/**
 * The class register route for public form submiting endpoint - mailchimp
 *
 * @package AndbrandWpPluginBlockFormsBase\Rest\Routes
 */

declare(strict_types=1);

namespace AndbrandWpPluginBlockFormsBase\Rest\Routes;

use AndbrandWpPluginBlockFormsBase\Integrations\Mailchimp\MailchimpClientInterface;
use AndbrandWpPluginBlockFormsBase\Integrations\Mailchimp\SettingsMailchimp;
use AndbrandWpPluginBlockFormsBase\Labels\LabelsInterface;
use AndbrandWpPluginBlockFormsBase\Validation\ValidatorInterface;

/**
 * Class FormSubmitMailchimpRoute
 */
class FormSubmitMailchimpRoute extends AbstractFormSubmit
{
	/**
	 * Instance variable of ValidatorInterface data.
	 *
	 * @var ValidatorInterface
	 */
	public $validator;

	/**
	 * Instance variable of LabelsInterface data.
	 *
	 * @var LabelsInterface
	 */
	protected $labels;

	/**
	 * Instance variable for Mailchimp data.
	 *
	 * @var MailchimpClientInterface
	 */
	protected $mailchimpClient;

	/**
	 * Create a new instance that injects classes
	 *
	 * @param ValidatorInterface $validator Inject ValidatorInterface which holds validation methods.
	 * @param LabelsInterface $labels Inject LabelsInterface which holds labels data.
	 * @param MailchimpClientInterface $mailchimpClient Inject Mailchimp which holds Mailchimp connect data.
	 */
	public function __construct(
		ValidatorInterface $validator,
		LabelsInterface $labels,
		MailchimpClientInterface $mailchimpClient
	) {
		$this->validator = $validator;
		$this->labels = $labels;
		$this->mailchimpClient = $mailchimpClient;
	}

	/**
	 * Get the base url of the route
	 *
	 * @return string The base URL for route you are adding.
	 */
	protected function getRouteName(): string
	{
		return '/form-submit-mailchimp';
	}

	/**
	 * Implement submit action.
	 *
	 * @param string $formId Form ID.
	 * @param array<string, mixed> $params Params array.
	 * @param array<string, array<int, array<string, mixed>>> $files Files array.
	 *
	 * @return mixed
	 */
	public function submitAction(string $formId, array $params = [], $files = [])
	{
		// Check if Mailchimp data is set and valid.
		$isSettingsValid = \apply_filters(SettingsMailchimp::FILTER_SETTINGS_IS_VALID_NAME, $formId);

		// Bailout if settings are not ok.
		if (!$isSettingsValid) {
			return \rest_ensure_response([
				'status' => 'error',
				'code' => 400,
				'message' => $this->labels->getLabel('mailchimpErrorSettingsMissing', $formId),
			]);
		}

		// Send application to Mailchimp.
		$response = $this->mailchimpClient->postApplication(
			$this->getSettingsValue(SettingsMailchimp::SETTINGS_MAILCHIMP_LIST_KEY, $formId),
			$params,
			[],
			$formId
		);

		// Finish.
		return \rest_ensure_response([
			'code' => $response['code'],
			'status' => $response['status'],
			'message' => $this->labels->getLabel($response['message'], $formId),
		]);
	}
}
