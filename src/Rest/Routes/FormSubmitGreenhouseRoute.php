<?php

/**
 * The class register route for public form submiting endpoint - Greenhouse
 *
 * @package AndbrandWpPluginBlockFormsBase\Rest\Routes
 */

declare(strict_types=1);

namespace AndbrandWpPluginBlockFormsBase\Rest\Routes;

use AndbrandWpPluginBlockFormsBase\Settings\SettingsHelper;
use AndbrandWpPluginBlockFormsBase\Helpers\UploadHelper;
use AndbrandWpPluginBlockFormsBase\Integrations\Greenhouse\SettingsGreenhouse;
use AndbrandWpPluginBlockFormsBase\Integrations\ClientInterface;
use AndbrandWpPluginBlockFormsBase\Labels\LabelsInterface;
use AndbrandWpPluginBlockFormsBase\Validation\ValidatorInterface;

/**
 * Class FormSubmitGreenhouseRoute
 */
class FormSubmitGreenhouseRoute extends AbstractFormSubmit
{
	/**
	 * Use trait Upload_Helper inside class.
	 */
	use UploadHelper;

	/**
	 * Use general helper trait.
	 */
	use SettingsHelper;

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
	 * Instance variable of ClientInterface data.
	 *
	 * @var ClientInterface
	 */
	protected $greenhouseClient;

	/**
	 * Create a new instance that injects classes
	 *
	 * @param ValidatorInterface $validator Inject ValidatorInterface which holds validation methods.
	 * @param LabelsInterface $labels Inject LabelsInterface which holds labels data.
	 * @param ClientInterface $greenhouseClient Inject ClientInterface which holds Greenhouse connect data.
	 */
	public function __construct(
		ValidatorInterface $validator,
		LabelsInterface $labels,
		ClientInterface $greenhouseClient
	) {
		$this->validator = $validator;
		$this->labels = $labels;
		$this->greenhouseClient = $greenhouseClient;
	}

	/**
	 * Get the base url of the route
	 *
	 * @return string The base URL for route you are adding.
	 */
	protected function getRouteName(): string
	{
		return '/form-submit-greenhouse';
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

		// Check if Greenhouse data is set and valid.
		$isSettingsValid = \apply_filters(SettingsGreenhouse::FILTER_SETTINGS_IS_VALID_NAME, $formId);

		// Bailout if settings are not ok.
		if (!$isSettingsValid) {
			return \rest_ensure_response([
				'status' => 'error',
				'code' => 400,
				'message' => $this->labels->getLabel('greenhouseErrorSettingsMissing', $formId),
			]);
		}

		// Send application to Greenhouse.
		$response = $this->greenhouseClient->postApplication(
			$this->getSettingsValue(SettingsGreenhouse::SETTINGS_GREENHOUSE_JOB_ID_KEY, $formId),
			$params,
			$files,
			$formId
		);

		// Always delete the files from the disk.
		if ($files) {
			$this->deleteFiles($files);
		}

		// Finish.
		return \rest_ensure_response([
			'code' => $response['code'],
			'status' => $response['status'],
			'message' => $this->labels->getLabel($response['message'], $formId),
		]);
	}
}
