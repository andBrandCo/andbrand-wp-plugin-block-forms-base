<?php

/**
 * Test Settings class.
 *
 * @package SebFormsWpPlugin\Settings\Settings
 */

declare(strict_types=1);

namespace SebFormsWpPlugin\Settings\Settings;

use SebFormsWpPlugin\Hooks\Filters;
use SebFormsWpPlugin\Settings\SettingsHelper;
use SebFormsWpPluginVendor\EightshiftLibs\Services\ServiceInterface;

/**
 * SettingsTest class.
 */
class SettingsTest implements SettingsDataInterface, ServiceInterface
{
	/**
	 * Use general helper trait.
	 */
	use SettingsHelper;

	/**
	 * Filter settings sidebar key.
	 */
	public const FILTER_SETTINGS_SIDEBAR_NAME = 'es_forms_settings_sidebar_test';

	/**
	 * Filter global settings key.
	 */
	public const FILTER_SETTINGS_GLOBAL_NAME = 'es_forms_settings_global_test';

	/**
	 * Settings key.
	 */
	public const SETTINGS_TYPE_KEY = 'test';

	/**
	 * Register all the hooks
	 *
	 * @return void
	 */
	public function register(): void
	{
		\add_filter(self::FILTER_SETTINGS_SIDEBAR_NAME, [$this, 'getSettingsSidebar']);
		\add_filter(self::FILTER_SETTINGS_GLOBAL_NAME, [$this, 'getSettingsGlobalData']);
	}

	/**
	 * Get Settings sidebar data.
	 *
	 * @return array<string, mixed>
	 */
	public function getSettingsSidebar(): array
	{
		return [
			'label' => \__('Test', 'seb-forms'),
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
		return [];
	}

	/**
	 * Get global settings array for building settings page.
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function getSettingsGlobalData(): array
	{
		return [
			[
				'component' => 'input',
				'inputId' => $this->getSettingsName('input-id'),
				'inputFieldLabel' => \__('input label', 'seb-forms'),
				'inputFieldHelp' => \__('help field input', 'seb-forms'),
				'inputType' => 'text',
				'inputValue' => $this->getOptionValue('input-id'),
			],
			[
				'component' => 'input',
				'inputId' => $this->getSettingsName('input-email-id'),
				'inputFieldLabel' => \__('input email label', 'seb-forms'),
				'inputFieldHelp' => \__('help field input email', 'seb-forms'),
				'inputType' => 'text',
				'inputValue' => $this->getOptionValue('input-email-id'),
				'inputIsEmail' => true,
			],
			[
				'component' => 'input',
				'inputId' => $this->getSettingsName('input-number-id'),
				'inputFieldLabel' => \__('input number label', 'seb-forms'),
				'inputFieldHelp' => \__('help field input number', 'seb-forms'),
				'inputType' => 'number',
				'inputValue' => $this->getOptionValue('input-number-id'),
			],
			[
				'component' => 'input',
				'inputId' => $this->getSettingsName('input-url-id'),
				'inputFieldLabel' => \__('input url label', 'seb-forms'),
				'inputFieldHelp' => \__('help field input url', 'seb-forms'),
				'inputType' => 'text',
				'inputValue' => $this->getOptionValue('input-url-id'),
				'inputIsUrl' => true,
			],
			[
				'component' => 'input',
				'inputId' => $this->getSettingsName('input-hidden-id'),
				'inputFieldLabel' => \__('input hidden label', 'seb-forms'),
				'inputFieldHelp' => \__('help field input hidden', 'seb-forms'),
				'inputType' => 'hidden',
				'inputValue' => $this->getOptionValue('input-hidden-id'),
			],
			[
				'component' => 'input',
				'inputId' => $this->getSettingsName('input-required-id'),
				'inputFieldLabel' => \__('input required label', 'seb-forms'),
				'inputFieldHelp' => \__('help field input required', 'seb-forms'),
				'inputType' => 'text',
				'inputValue' => $this->getOptionValue('input-required-id'),
				'inputIsRequired' => true,
			],
			[
				'component' => 'textarea',
				'textareaId' => $this->getSettingsName('textarea-id'),
				'textareaFieldLabel' => \__('textarea label', 'seb-forms'),
				'textareaFieldHelp' => \__('help field textarea', 'seb-forms'),
				'textareaValue' => $this->getOptionValue('textarea-id'),
			],
			[
				'component' => 'textarea',
				'textareaId' => $this->getSettingsName('textarea-required-id'),
				'textareaFieldLabel' => \__('textarea required label', 'seb-forms'),
				'textareaFieldHelp' => \__('help field textarea required', 'seb-forms'),
				'textareaValue' => $this->getOptionValue('textarea-required-id'),
				'textareaIsRequired' => true,
			],
			[
				'component' => 'select',
				'selectId' => $this->getSettingsName('select-id'),
				'selectFieldLabel' => \__('select label', 'seb-forms'),
				'selectFieldHelp' => \__('help field select', 'seb-forms'),
				'selectValue' => $this->getOptionValue('select-id'),
				'selectOptions' => [
					[
						'component' => 'select-option',
						'selectOptionLabel' => 'select-label1',
						'selectOptionValue' => 'select-id1',
						'selectOptionIsSelected' => $this->isCheckedOption('select-id1', 'select-id'),
					],
					[
						'component' => 'select-option',
						'selectOptionLabel' => 'select-label2',
						'selectOptionValue' => 'select-id2',
						'selectOptionIsSelected' => $this->isCheckedOption('select-id2', 'select-id'),
					],
				]
			],
			[
				'component' => 'select',
				'selectId' => $this->getSettingsName('select-required-id'),
				'selectFieldLabel' => \__('select required label', 'seb-forms'),
				'selectFieldHelp' => \__('help field select required', 'seb-forms'),
				'selectValue' => $this->getOptionValue('select-required-id'),
				'selectIsRequired' => true,
				'selectOptions' => [
					[
						'component' => 'select-option',
						'selectOptionLabel' => '---',
						'selectOptionValue' => '',
						'selectOptionIsSelected' => $this->isCheckedOption('select-required-id1', 'select-required-id'),
					],
					[
						'component' => 'select-option',
						'selectOptionLabel' => 'select-label1',
						'selectOptionValue' => 'select-id1',
						'selectOptionIsSelected' => $this->isCheckedOption('select-id1', 'select-required-id'),
					],
					[
						'component' => 'select-option',
						'selectOptionLabel' => 'select-label2',
						'selectOptionValue' => 'select-id2',
						'selectOptionIsSelected' => $this->isCheckedOption('select-id2', 'select-required-id'),
					],
				]
			],
			[
				'component' => 'checkboxes',
				'checkboxesFieldLabel' => \__('checkboxes', 'seb-forms'),
				'checkboxesFieldHelp' => \__('help field checkboxes', 'seb-forms'),
				'checkboxesId' => $this->getSettingsName('checkbox-id'),
				'checkboxesName' => $this->getSettingsName('checkbox-name'),
				'checkboxesIsRequired' => true,
				'checkboxesContent' => [
					[
						'component' => 'checkbox',
						'checkboxLabel' => \__('checkbox label 1', 'seb-forms'),
						'checkboxValue' => 'checkbox-id1',
						'checkboxIsChecked' => $this->isCheckboxOptionChecked('checkbox-id1', 'checkbox-id'),
						'checkboxSingleSubmit' => true,
					],
					[
						'component' => 'checkbox',
						'checkboxLabel' => \__('checkbox required label 2', 'seb-forms'),
						'checkboxValue' => 'checkbox-required-id2',
						'checkboxIsChecked' => $this->isCheckboxOptionChecked('checkbox-required-id2', 'checkbox-id'),
					],
					[
						'component' => 'checkbox',
						'checkboxLabel' => \__('checkbox disabled label 3', 'seb-forms'),
						'checkboxValue' => 'checkbox-disabled-id3',
						'checkboxIsChecked' => $this->isCheckboxOptionChecked('checkbox-disabled-id3', 'checkbox-id'),
						'checkboxIsDisabled' => true,
					],
					[
						'component' => 'checkbox',
						'checkboxLabel' => \__('checkbox readonly label 4', 'seb-forms'),
						'checkboxValue' => 'checkbox-readonly-id4',
						'checkboxIsReadOnly' => true,
						'checkboxIsChecked' => $this->isCheckboxOptionChecked('checkbox-readonly-id4', 'checkbox-id'),
					],
				]
			],
			[
				'component' => 'checkboxes',
				'checkboxesFieldLabel' => \__('checkboxes count', 'seb-forms'),
				'checkboxesFieldHelp' => \__('help field checkboxes', 'seb-forms'),
				'checkboxesId' => $this->getSettingsName('checkbox-count-id'),
				'checkboxesName' => $this->getSettingsName('checkbox-count-name'),
				'checkboxesIsRequired' => true,
				'checkboxesIsRequiredCount' => 2,
				'checkboxesContent' => [
					[
						'component' => 'checkbox',
						'checkboxLabel' => \__('checkbox label 1', 'seb-forms'),
						'checkboxValue' => 'checkbox-id1',
						'checkboxIsChecked' => $this->isCheckboxOptionChecked('checkbox-required-id1', 'checkbox-count-id'),
					],
					[
						'component' => 'checkbox',
						'checkboxLabel' => \__('checkbox required label 2', 'seb-forms'),
						'checkboxValue' => 'checkbox-required-id2',
						'checkboxIsChecked' => $this->isCheckboxOptionChecked('checkbox-required-id2', 'checkbox-count-id'),
					],
					[
						'component' => 'checkbox',
						'checkboxLabel' => \__('checkbox disabled label 3', 'seb-forms'),
						'checkboxValue' => 'checkbox-disabled-id3',
						'checkboxIsChecked' => $this->isCheckboxOptionChecked('checkbox-required-id3', 'checkbox-count-id'),
					],
					[
						'component' => 'checkbox',
						'checkboxLabel' => \__('checkbox readonly label 4', 'seb-forms'),
						'checkboxValue' => 'checkbox-readonly-id4',
						'checkboxIsChecked' => $this->isCheckboxOptionChecked('checkbox-required-id4', 'checkbox-count-id'),
					],
				]
			],
			[
				'component' => 'radios',
				'radiosFieldLabel' => \__('radios', 'seb-forms'),
				'radiosFieldHelp' => \__('help field radios', 'seb-forms'),
				'radiosId' => $this->getSettingsName('radio-id'),
				'radiosName' => $this->getSettingsName('radio-name'),
				'radiosContent' => [
					[
						'component' => 'radio',
						'radioLabel' => \__('radio label 1', 'seb-forms'),
						'radioValue' => 'radio-id1',
						'radioIsChecked' => $this->isCheckedOption('radio-id1', 'radio-id'),
					],
					[
						'component' => 'radio',
						'radioLabel' => \__('radio disabled label 2', 'seb-forms'),
						'radioValue' => 'radio-disabled-id2',
						'radioIsChecked' => $this->isCheckedOption('radio-disabled-id2', 'radio-id'),
						'radioIsDisabled' => true,
					],
					[
						'component' => 'radio',
						'radioLabel' => \__('radio disabled checked label 3', 'seb-forms'),
						'radioValue' => 'radio-disabled-checked-id3',
						'radioIsChecked' => $this->isCheckedOption('radio-disabled-checked-id3', 'radio-id'),
						'radioIsDisabled' => true,
					],
				]
			],
			[
				'component' => 'radios',
				'radiosFieldLabel' => \__('radios required', 'seb-forms'),
				'radiosFieldHelp' => \__('help field radios required', 'seb-forms'),
				'radiosId' => $this->getSettingsName('radio-required-id'),
				'radiosName' => $this->getSettingsName('radio-required-name'),
				'radiosIsRequired' => true,
				'radiosContent' => [
					[
						'component' => 'radio',
						'radioLabel' => \__('radio required label 1', 'seb-forms'),
						'radioValue' => 'radio-required-id1',
						'radioIsChecked' => $this->isCheckedOption('radio-required-id1', 'radio-required-id'),
					],
					[
						'component' => 'radio',
						'radioLabel' => \__('radio required label 2', 'seb-forms'),
						'radioValue' => 'radio-required-id2',
						'radioIsChecked' => $this->isCheckedOption('radio-required-id2', 'radio-required-id'),
					],
				]
			],
		];
	}
}
