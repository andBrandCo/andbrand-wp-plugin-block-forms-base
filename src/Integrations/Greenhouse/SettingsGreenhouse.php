<?php

/**
 * Greenhouse Settings class.
 *
 * @package AndbrandWpPluginBlockFormsBase\Integrations\Greenhouse
 */

declare(strict_types=1);

namespace AndbrandWpPluginBlockFormsBase\Integrations\Greenhouse;

use AndbrandWpPluginBlockFormsBaseVendor\EightshiftLibs\Helpers\Components;
use AndbrandWpPluginBlockFormsBase\Helpers\Helper;
use AndbrandWpPluginBlockFormsBase\Hooks\Filters;
use AndbrandWpPluginBlockFormsBase\Settings\SettingsHelper;
use AndbrandWpPluginBlockFormsBase\Hooks\Variables;
use AndbrandWpPluginBlockFormsBase\Integrations\ClientInterface;
use AndbrandWpPluginBlockFormsBase\Integrations\MapperInterface;
use AndbrandWpPluginBlockFormsBase\Settings\Settings\SettingsDataInterface;
use AndbrandWpPluginBlockFormsBaseVendor\EightshiftLibs\Services\ServiceInterface;

/**
 * SettingsGreenhouse class.
 */
class SettingsGreenhouse implements SettingsDataInterface, ServiceInterface
{
	/**
	 * Use general helper trait.
	 */
	use SettingsHelper;

	/**
	 * Filter settings sidebar key.
	 */
	public const FILTER_SETTINGS_SIDEBAR_NAME = 'es_forms_settings_sidebar_greenhouse';

	/**
	 * Filter settings key.
	 */
	public const FILTER_SETTINGS_NAME = 'es_forms_settings_greenhouse';

	/**
	 * Filter global settings key.
	 */
	public const FILTER_SETTINGS_GLOBAL_NAME = 'es_forms_settings_global_greenhouse';

	/**
	 * Filter settings is Valid key.
	 */
	public const FILTER_SETTINGS_IS_VALID_NAME = 'es_forms_settings_is_valid_greenhouse';

	/**
	 * Settings key.
	 */
	public const SETTINGS_TYPE_KEY = 'greenhouse';

	/**
	 * Greenhouse Use key.
	 */
	public const SETTINGS_GREENHOUSE_USE_KEY = 'greenhouse-use';

	/**
	 * API Key.
	 */
	public const SETTINGS_GREENHOUSE_API_KEY_KEY = 'greenhouse-api-key';

	/**
	 * Board Token Key.
	 */
	public const SETTINGS_GREENHOUSE_BOARD_TOKEN_KEY = 'greenhouse-board-token';

	/**
	 * Job ID Key.
	 */
	public const SETTINGS_GREENHOUSE_JOB_ID_KEY = 'greenhouse-job-id';

	/**
	 * Integration fields Key.
	 */
	public const SETTINGS_GREENHOUSE_INTEGRATION_FIELDS_KEY = 'greenhouse-integration-fields';

	/**
	 * Instance variable for Greenhouse data.
	 *
	 * @var ClientInterface
	 */
	protected $greenhouseClient;

	/**
	 * Instance variable for Greenhouse form data.
	 *
	 * @var MapperInterface
	 */
	protected $greenhouse;

	/**
	 * Create a new instance.
	 *
	 * @param ClientInterface $greenhouseClient Inject Greenhouse which holds Greenhouse connect data.
	 * @param MapperInterface $greenhouse Inject Greenhouse which holds Greenhouse form data.
	 */
	public function __construct(
		ClientInterface $greenhouseClient,
		MapperInterface $greenhouse
	) {
		$this->greenhouseClient = $greenhouseClient;
		$this->greenhouse = $greenhouse;
	}

	/**
	 * Register all the hooks
	 *
	 * @return void
	 */
	public function register(): void
	{
		\add_filter(self::FILTER_SETTINGS_SIDEBAR_NAME, [$this, 'getSettingsSidebar']);
		\add_filter(self::FILTER_SETTINGS_NAME, [$this, 'getSettingsData']);
		\add_filter(self::FILTER_SETTINGS_GLOBAL_NAME, [$this, 'getSettingsGlobalData']);
		\add_filter(self::FILTER_SETTINGS_IS_VALID_NAME, [$this, 'isSettingsValid']);
	}

	/**
	 * Determine if settings are valid.
	 *
	 * @param string $formId Form ID.
	 *
	 * @return boolean
	 */
	public function isSettingsValid(string $formId): bool
	{
		if (!$this->isSettingsGlobalValid()) {
			return false;
		}

		$jobKey = $this->getSettingsValue(self::SETTINGS_GREENHOUSE_JOB_ID_KEY, $formId);

		if (empty($jobKey)) {
			return false;
		}

		return true;
	}

	/**
	 * Determine if settings global are valid.
	 *
	 * @return boolean
	 */
	public function isSettingsGlobalValid(): bool
	{
		$isUsed = $this->isCheckboxOptionChecked(self::SETTINGS_GREENHOUSE_USE_KEY, self::SETTINGS_GREENHOUSE_USE_KEY);
		$apiKey = !empty(Variables::getApiKeyGreenhouse()) ? Variables::getApiKeyGreenhouse() : $this->getOptionValue(self::SETTINGS_GREENHOUSE_API_KEY_KEY);
		$boardToken = !empty(Variables::getBoardTokenGreenhouse()) ? Variables::getBoardTokenGreenhouse() : $this->getOptionValue(self::SETTINGS_GREENHOUSE_BOARD_TOKEN_KEY);

		if (!$isUsed || empty($apiKey) || empty($boardToken)) {
			return false;
		}

		return true;
	}

	/**
	 * Get Settings sidebar data.
	 *
	 * @return array<string, mixed>
	 */
	public function getSettingsSidebar(): array
	{
		return [
			'label' => \__('Greenhouse', 'andbrand-block-forms-base'),
			'value' => self::SETTINGS_TYPE_KEY,
			'icon' => Filters::ALL[self::SETTINGS_TYPE_KEY]['icon'],
		];
	}

	/**
	 * Get Form settings data array
	 *
	 * @param string $formId Form Id.
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function getSettingsData(string $formId): array
	{
		if (!$this->isSettingsGlobalValid()) {
			return [
				[
					'component' => 'highlighted-content',
					'highlightedContentTitle' => \__('Some config required', 'andbrand-block-forms-base'),
					// translators: %s will be replaced with the global settings url.
					'highlightedContentSubtitle' => \sprintf(\__('Before using Greenhouse you need to configure it in  <a href="%s">global settings</a>.', 'andbrand-block-forms-base'), Helper::getSettingsGlobalPageUrl(self::SETTINGS_TYPE_KEY)),
					'highlightedContentIcon' => 'tools',
				]
			];
		}

		$items = $this->greenhouseClient->getItems(false);
		$lastUpdatedTime = $items[ClientInterface::TRANSIENT_STORED_TIME]['title'] ?? '';
		unset($items[ClientInterface::TRANSIENT_STORED_TIME]);

		if (!$items) {
			return [
				[
					'component' => 'highlighted-content',
					'highlightedContentTitle' => \__('Something went wrong', 'andbrand-block-forms-base'),
					'highlightedContentSubtitle' => \__('Data from Greenhouse couldn\'t be fetched. Check the API key.', 'andbrand-block-forms-base'),
					'highlightedContentIcon' => 'error',
				],
			];
		}

		$itemOptions = \array_map(
			function ($option) use ($formId) {
				return [
					'component' => 'select-option',
					'selectOptionLabel' => $option['title'] ?? '',
					'selectOptionValue' => $option['id'] ?? '',
					'selectOptionIsSelected' => $this->isCheckedSettings($option['id'], self::SETTINGS_GREENHOUSE_JOB_ID_KEY, $formId),
				];
			},
			$items
		);

		\array_unshift(
			$itemOptions,
			[
				'component' => 'select-option',
				'selectOptionLabel' => '',
				'selectOptionValue' => '',
			]
		);

		$selectedItem = $this->getSettingsValue(self::SETTINGS_GREENHOUSE_JOB_ID_KEY, $formId);

		$manifestForm = Components::getManifest(\dirname(__DIR__, 2) . '/Blocks/components/form');

		$output = [
			[
				'component' => 'intro',
				'introTitle' => \__('Greenhouse', 'andbrand-block-forms-base'),
			],
			[
				'component' => 'select',
				'selectName' => $this->getSettingsName(self::SETTINGS_GREENHOUSE_JOB_ID_KEY),
				'selectId' => $this->getSettingsName(self::SETTINGS_GREENHOUSE_JOB_ID_KEY),
				'selectFieldLabel' => \__('Job post', 'andbrand-block-forms-base'),
				// translators: %1$s will be replaced with js selector, %2$s will be replaced with the cache type, %3$s will be replaced with latest update time.
				'selectFieldHelp' => \sprintf(\__('If a job post isn\'t showing up or is missing some jobs, try <a href="#" class="%1$s" data-type="%2$s">clearing the cache</a>. Last updated: %3$s.', 'andbrand-block-forms-base'), $manifestForm['componentCacheJsClass'], self::SETTINGS_TYPE_KEY, $lastUpdatedTime),
				'selectOptions' => $itemOptions,
				'selectIsRequired' => true,
				'selectValue' => $selectedItem,
				'selectSingleSubmit' => true,
			],
		];

		// If the user has selected the list.
		if ($selectedItem) {
			$beforeContent = '';

			$filterName = Filters::getIntegrationFilterName(self::SETTINGS_TYPE_KEY, 'adminFieldsSettings');
			if (\has_filter($filterName)) {
				$beforeContent = \apply_filters($filterName, '') ?? '';
			}

			$output = \array_merge(
				$output,
				[
					[
						'component' => 'divider',
					],
					[
						'component' => 'intro',
						'introTitle' => \__('Form fields', 'andbrand-block-forms-base'),
						'introTitleSize' => 'medium',
						'introSubtitle' => \__('Control which fields show up on the frontend, set up how they look and work.', 'andbrand-block-forms-base'),
					],
					[
						'component' => 'group',
						'groupId' => $this->getSettingsName(self::SETTINGS_GREENHOUSE_INTEGRATION_FIELDS_KEY),
						'groupBeforeContent' => $beforeContent,
						'groupStyle' => 'integration',
						'groupContent' => $this->getIntegrationFieldsDetails(
							self::SETTINGS_GREENHOUSE_INTEGRATION_FIELDS_KEY,
							self::SETTINGS_TYPE_KEY,
							$this->greenhouse->getFormFields($formId),
							$formId
						),
					]
				]
			);
		}

		return $output;
	}

	/**
	 * Get global settings array for building settings page.
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function getSettingsGlobalData(): array
	{
		$isUsed = $this->isCheckboxOptionChecked(self::SETTINGS_GREENHOUSE_USE_KEY, self::SETTINGS_GREENHOUSE_USE_KEY);

		$output = [
			[
				'component' => 'intro',
				'introTitle' => \__('Greenhouse', 'andbrand-block-forms-base'),
			],
			[
				'component' => 'intro',
				'introTitle' => \__('How to get the API key?', 'andbrand-block-forms-base'),
				'introTitleSize' => 'small',
				// phpcs:ignore WordPress.WP.I18n.NoHtmlWrappedStrings
				'introSubtitle' => \__('<ol>
						<li>Log in to your Greenhouse Account.</li>
						<li>Go to <a target="_blank" href="https://app.greenhouse.io/configure/dev_center/credentials">API Credentials Settings</a>.</li>
						<li>Click on <strong>Create New API Key</strong>.</li>
						<li>Select <strong>Job Board</strong> as your API Type.</li>
						<li>Copy the API key into the field below or use the global constant.</li>
					</ol>', 'andbrand-block-forms-base'),
			],
			[
				'component' => 'intro',
				'introTitle' => \__('How to get the Job Board name?', 'andbrand-block-forms-base'),
				'introTitleSize' => 'small',
				// phpcs:ignore WordPress.WP.I18n.NoHtmlWrappedStrings
				'introSubtitle' => \__('<ol>
						<li>Log in to your Greenhouse Account.</li>
						<li>Go to <a target="_blank" href="https://app.greenhouse.io/jobboard">Job Boards Settings</a>.</li>
						<li>Copy the <strong>Board Name</strong> you want to use.</li>
						<li>Make the name all lowercase.</li>
						<li>Copy the Board Name into the field below or use the global constant.</li>
					</ol>', 'andbrand-block-forms-base'),
			],
			[
				'component' => 'divider',
			],
			[
				'component' => 'checkboxes',
				'checkboxesFieldLabel' => '',
				'checkboxesName' => $this->getSettingsName(self::SETTINGS_GREENHOUSE_USE_KEY),
				'checkboxesId' => $this->getSettingsName(self::SETTINGS_GREENHOUSE_USE_KEY),
				'checkboxesIsRequired' => true,
				'checkboxesContent' => [
					[
						'component' => 'checkbox',
						'checkboxLabel' => \__('Use Greenhouse', 'andbrand-block-forms-base'),
						'checkboxIsChecked' => $this->isCheckboxOptionChecked(self::SETTINGS_GREENHOUSE_USE_KEY, self::SETTINGS_GREENHOUSE_USE_KEY),
						'checkboxValue' => self::SETTINGS_GREENHOUSE_USE_KEY,
						'checkboxSingleSubmit' => true,
					]
				]
			],
		];

		if ($isUsed) {
			$apiKey = Variables::getApiKeyGreenhouse();
			$boardToken = Variables::getBoardTokenGreenhouse();

			$output = \array_merge(
				$output,
				[
					[
						'component' => 'input',
						'inputName' => $this->getSettingsName(self::SETTINGS_GREENHOUSE_API_KEY_KEY),
						'inputId' => $this->getSettingsName(self::SETTINGS_GREENHOUSE_API_KEY_KEY),
						'inputFieldLabel' => \__('API key', 'andbrand-block-forms-base'),
						'inputFieldHelp' => \__('Can also be provided via a global variable.', 'andbrand-block-forms-base'),
						'inputType' => 'password',
						'inputIsRequired' => true,
						'inputValue' => !empty($apiKey) ? 'xxxxxxxxxxxxxxxx' : $this->getOptionValue(self::SETTINGS_GREENHOUSE_API_KEY_KEY),
						'inputIsDisabled' => !empty($apiKey),
					],
					[
						'component' => 'input',
						'inputName' => $this->getSettingsName(self::SETTINGS_GREENHOUSE_BOARD_TOKEN_KEY),
						'inputId' => $this->getSettingsName(self::SETTINGS_GREENHOUSE_BOARD_TOKEN_KEY),
						'inputFieldLabel' => \__('Job Board name', 'andbrand-block-forms-base'),
						'inputFieldHelp' => \__('Can also be provided via a global variable.', 'andbrand-block-forms-base'),
						'inputType' => 'text',
						'inputIsRequired' => true,
						'inputValue' => !empty($boardToken) ? $boardToken : $this->getOptionValue(self::SETTINGS_GREENHOUSE_BOARD_TOKEN_KEY),
						'inputIsDisabled' => !empty($boardToken),
					],
				]
			);
		}

		return $output;
	}
}
