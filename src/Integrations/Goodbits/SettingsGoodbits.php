<?php

/**
 * Goodbits Settings class.
 *
 * @package AndbrandWpPluginBlockFormsBase\Integrations\Goodbits
 */

declare(strict_types=1);

namespace AndbrandWpPluginBlockFormsBase\Integrations\Goodbits;

use AndbrandWpPluginBlockFormsBase\Helpers\Helper;
use AndbrandWpPluginBlockFormsBase\Hooks\Filters;
use AndbrandWpPluginBlockFormsBase\Settings\SettingsHelper;
use AndbrandWpPluginBlockFormsBase\Hooks\Variables;
use AndbrandWpPluginBlockFormsBase\Integrations\ClientInterface;
use AndbrandWpPluginBlockFormsBase\Integrations\MapperInterface;
use AndbrandWpPluginBlockFormsBase\Settings\Settings\SettingsDataInterface;
use AndbrandWpPluginBlockFormsBaseVendor\EightshiftLibs\Services\ServiceInterface;

/**
 * SettingsGoodbits class.
 */
class SettingsGoodbits implements SettingsDataInterface, ServiceInterface
{
	/**
	 * Use general helper trait.
	 */
	use SettingsHelper;

	/**
	 * Filter settings sidebar key.
	 */
	public const FILTER_SETTINGS_SIDEBAR_NAME = 'es_forms_settings_sidebar_goodbits';

	/**
	 * Filter settings key.
	 */
	public const FILTER_SETTINGS_NAME = 'es_forms_settings_goodbits';

	/**
	 * Filter global settings key.
	 */
	public const FILTER_SETTINGS_GLOBAL_NAME = 'es_forms_settings_global_goodbits';

	/**
	 * Filter settings is Valid key.
	 */
	public const FILTER_SETTINGS_IS_VALID_NAME = 'es_forms_settings_is_valid_goodbits';

	/**
	 * Settings key.
	 */
	public const SETTINGS_TYPE_KEY = 'goodbits';

	/**
	 * Goodbits Use key.
	 */
	public const SETTINGS_GOODBITS_USE_KEY = 'goodbits-use';

	/**
	 * API Key.
	 */
	public const SETTINGS_GOODBITS_API_KEY_KEY = 'goodbits-api-key';

	/**
	 * List ID Key.
	 */
	public const SETTINGS_GOODBITS_LIST_KEY = 'goodbits-list';

	/**
	 * Integration fields Key.
	 */
	public const SETTINGS_GOODBITS_INTEGRATION_FIELDS_KEY = 'goodbits-integration-fields';

	/**
	 * Instance variable for Goodbits data.
	 *
	 * @var ClientInterface
	 */
	protected $goodbitsClient;

	/**
	 * Instance variable for Goodbits form data.
	 *
	 * @var MapperInterface
	 */
	protected $goodbits;

	/**
	 * Create a new instance.
	 *
	 * @param ClientInterface $goodbitsClient Inject Goodbits which holds Goodbits connect data.
	 * @param MapperInterface $goodbits Inject Goodbits which holds Goodbits form data.
	 */
	public function __construct(
		ClientInterface $goodbitsClient,
		MapperInterface $goodbits
	) {
		$this->goodbitsClient = $goodbitsClient;
		$this->goodbits = $goodbits;
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

		$list = $this->getSettingsValue(SettingsGoodbits::SETTINGS_GOODBITS_LIST_KEY, $formId);

		if (empty($list)) {
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
		$isUsed = $this->isCheckboxOptionChecked(self::SETTINGS_GOODBITS_USE_KEY, self::SETTINGS_GOODBITS_USE_KEY);
		$apiKey = !empty(Variables::getApiKeyGoodbits()) ? Variables::getApiKeyGoodbits() : $this->getOptionValue(self::SETTINGS_GOODBITS_API_KEY_KEY);

		if (!$isUsed || empty($apiKey)) {
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
			'label' => \__('Goodbits', 'andbrand-block-forms-base'),
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
					'highlightedContentSubtitle' => \sprintf(\__('Before using Goodbits you need to configure it in  <a href="%s">global settings</a>.', 'andbrand-block-forms-base'), Helper::getSettingsGlobalPageUrl(self::SETTINGS_TYPE_KEY)),
					'highlightedContentIcon' => 'tools',
				],
			];
		}

		$items = $this->goodbitsClient->getItems();

		if (!$items) {
			return [
				[
					'component' => 'highlighted-content',
					'highlightedContentTitle' => \__('Something went wrong', 'andbrand-block-forms-base'),
					'highlightedContentSubtitle' => \__('Data from Goodbits couldn\'t be fetched. Check the API key.', 'andbrand-block-forms-base'),
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
					'selectOptionIsSelected' => $this->isCheckedSettings($option['id'], self::SETTINGS_GOODBITS_LIST_KEY, $formId),
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

		$selectedItem = $this->getSettingsValue(self::SETTINGS_GOODBITS_LIST_KEY, $formId);

		$output = [
			[
				'component' => 'intro',
				'introTitle' => \__('Goodbits', 'andbrand-block-forms-base'),
			],
			[
				'component' => 'select',
				'selectName' => $this->getSettingsName(self::SETTINGS_GOODBITS_LIST_KEY),
				'selectId' => $this->getSettingsName(self::SETTINGS_GOODBITS_LIST_KEY),
				'selectFieldLabel' => \__('List', 'andbrand-block-forms-base'),
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
						'groupId' => $this->getSettingsName(self::SETTINGS_GOODBITS_INTEGRATION_FIELDS_KEY),
						'groupBeforeContent' => $beforeContent,
						'groupStyle' => 'integration',
						'groupContent' => $this->getIntegrationFieldsDetails(
							self::SETTINGS_GOODBITS_INTEGRATION_FIELDS_KEY,
							self::SETTINGS_TYPE_KEY,
							$this->goodbits->getFormFields($formId),
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
		$isUsed = $this->isCheckboxOptionChecked(self::SETTINGS_GOODBITS_USE_KEY, self::SETTINGS_GOODBITS_USE_KEY);

		$output = [
			[
				'component' => 'intro',
				'introTitle' => \__('Goodbits', 'andbrand-block-forms-base'),
			],
			[
				'component' => 'intro',
				'introTitle' => \__('How to get the API key?', 'andbrand-block-forms-base'),
				'introTitleSize' => 'small',
				// phpcs:ignore WordPress.WP.I18n.NoHtmlWrappedStrings
				'introSubtitle' => \__('<ol>
						<li>Log in to your Goodbits Account.</li>
						<li>Go to <strong>Settings</strong>, then click <strong><a target="_blank" href="https://app.Goodbits.com/integrations/api/">API</a></strong>.</li>
						<li>Copy the API key into the field below or use the global constant</li>
					</ol>', 'andbrand-block-forms-base'),
			],
			[
				'component' => 'divider',
			],
			[
				'component' => 'checkboxes',
				'checkboxesFieldLabel' => '',
				'checkboxesName' => $this->getSettingsName(self::SETTINGS_GOODBITS_USE_KEY),
				'checkboxesId' => $this->getSettingsName(self::SETTINGS_GOODBITS_USE_KEY),
				'checkboxesIsRequired' => true,
				'checkboxesContent' => [
					[
						'component' => 'checkbox',
						'checkboxLabel' => \__('Use Goodbits', 'andbrand-block-forms-base'),
						'checkboxIsChecked' => $this->isCheckboxOptionChecked(self::SETTINGS_GOODBITS_USE_KEY, self::SETTINGS_GOODBITS_USE_KEY),
						'checkboxValue' => self::SETTINGS_GOODBITS_USE_KEY,
						'checkboxSingleSubmit' => true,
					]
				]
			],
		];

		if ($isUsed) {
			$apiKey = Variables::getApiKeyGoodbits();

			$output = \array_merge(
				$output,
				[
					[
						'component' => 'input',
						'inputName' => $this->getSettingsName(self::SETTINGS_GOODBITS_API_KEY_KEY),
						'inputId' => $this->getSettingsName(self::SETTINGS_GOODBITS_API_KEY_KEY),
						'inputFieldLabel' => \__('API key', 'andbrand-block-forms-base'),
						'inputFieldHelp' => \__('Can also be provided via a global variable.', 'andbrand-block-forms-base'),
						'inputType' => 'password',
						'inputIsRequired' => true,
						'inputValue' => !empty($apiKey) ? 'xxxxxxxxxxxxxxxx' : $this->getOptionValue(self::SETTINGS_GOODBITS_API_KEY_KEY),
						'inputIsDisabled' => !empty($apiKey),
					],
				]
			);
		}

		return $output;
	}
}
