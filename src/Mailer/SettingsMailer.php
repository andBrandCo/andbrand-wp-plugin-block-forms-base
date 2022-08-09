<?php

/**
 * Mailer Settings class.
 *
 * @package SebFormsWpPlugin\Mailer
 */

declare(strict_types=1);

namespace SebFormsWpPlugin\Mailer;

use SebFormsWpPlugin\Helpers\Helper;
use SebFormsWpPlugin\Hooks\Filters;
use SebFormsWpPlugin\Settings\SettingsHelper;
use SebFormsWpPlugin\Settings\Settings\SettingsDataInterface;
use SebFormsWpPluginVendor\EightshiftLibs\Services\ServiceInterface;

/**
 * SettingsMailer class.
 */
class SettingsMailer implements SettingsDataInterface, ServiceInterface
{
	/**
	 * Use general helper trait.
	 */
	use SettingsHelper;

	/**
	 * Filter settings sidebar key.
	 */
	public const FILTER_SETTINGS_SIDEBAR_NAME = 'es_forms_settings_sidebar_mailer';

	/**
	 * Filter settings key.
	 */
	public const FILTER_SETTINGS_NAME = 'es_forms_settings_mailer';

	/**
	 * Filter settings is Valid key.
	 */
	public const FILTER_SETTINGS_IS_VALID_NAME = 'es_forms_settings_is_valid_mailer';

	/**
	 * Settings key.
	 */
	public const SETTINGS_TYPE_KEY = 'mailer';

	/**
	 * Mailer Use key.
	 */
	public const SETTINGS_MAILER_USE_KEY = 'mailer-use';

	/**
	 * Sender Name key.
	 */
	public const SETTINGS_MAILER_SENDER_NAME_KEY = 'mailer-sender-name';

	/**
	 * Sender Email key.
	 */
	public const SETTINGS_MAILER_SENDER_EMAIL_KEY = 'mailer-sender-email';

	/**
	 * Mail To key.
	 */
	public const SETTINGS_MAILER_TO_KEY = 'mailer-to';

	/**
	 * Subject key.
	 */
	public const SETTINGS_MAILER_SUBJECT_KEY = 'mailer-subject';

	/**
	 * Template key.
	 */
	public const SETTINGS_MAILER_TEMPLATE_KEY = 'mailer-template';

	/**
	 * Sender Subject key.
	 */
	public const SETTINGS_MAILER_SENDER_SUBJECT_KEY = 'mailer-sender-subject';

	/**
	 * Sender Template key.
	 */
	public const SETTINGS_MAILER_SENDER_TEMPLATE_KEY = 'mailer-sender-template';

	/**
	 * Register all the hooks
	 *
	 * @return void
	 */
	public function register(): void
	{
		\add_filter(self::FILTER_SETTINGS_SIDEBAR_NAME, [$this, 'getSettingsSidebar']);
		\add_filter(self::FILTER_SETTINGS_NAME, [$this, 'getSettingsData'], 10, 2);
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
		$senderName = $this->getSettingsValue(self::SETTINGS_MAILER_SENDER_NAME_KEY, $formId);
		$senderEmail = $this->getSettingsValue(self::SETTINGS_MAILER_SENDER_EMAIL_KEY, $formId);
		$to = $this->getSettingsValue(self::SETTINGS_MAILER_TO_KEY, $formId);
		$subject = $this->getSettingsValue(self::SETTINGS_MAILER_SUBJECT_KEY, $formId);

		if (
			empty($senderName) ||
			empty($senderEmail) ||
			empty($to) ||
			empty($subject)
		) {
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
			'label' => \__('Mailer', 'seb-forms'),
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
		$isUsed = (bool) $this->isCheckboxSettingsChecked(self::SETTINGS_MAILER_USE_KEY, self::SETTINGS_MAILER_USE_KEY, $formId);

		$output = [
			[
				'component' => 'intro',
				'introTitle' => \__('Mailer', 'seb-forms'),
				'introSubtitle' => \__('Sends simple e-mails.', 'seb-forms'),
			],
			[
				'component' => 'divider',
			],
			[
				'component' => 'checkboxes',
				'checkboxesFieldLabel' => '',
				'checkboxesName' => $this->getSettingsName(self::SETTINGS_MAILER_USE_KEY),
				'checkboxesId' => $this->getSettingsName(self::SETTINGS_MAILER_USE_KEY),
				'checkboxesContent' => [
					[
						'component' => 'checkbox',
						'checkboxLabel' => \__('Use Mailer', 'seb-forms'),
						'checkboxIsChecked' => $this->isCheckboxSettingsChecked(self::SETTINGS_MAILER_USE_KEY, self::SETTINGS_MAILER_USE_KEY, $formId),
						'checkboxValue' => self::SETTINGS_MAILER_USE_KEY,
						'checkboxSingleSubmit' => true,
					]
				]
			],
		];

		if ($isUsed) {
			$formNames = Helper::getFormNames($formId);

			$output = \array_merge(
				$output,
				[
					[
						'component' => 'divider',
					],
					[
						'component' => 'intro',
						'introTitle' => \__('E-mail settings', 'seb-forms'),
						'introTitleSize' => 'medium',
					],
					[
						'component' => 'input',
						'inputName' => $this->getSettingsName(self::SETTINGS_MAILER_SENDER_NAME_KEY),
						'inputId' => $this->getSettingsName(self::SETTINGS_MAILER_SENDER_NAME_KEY),
						'inputFieldLabel' => \__('Sender name', 'seb-forms'),
						'inputFieldHelp' => \__('Most e-mail clients show this instead of the e-mail address in the list of e-mails.', 'seb-forms'),
						'inputType' => 'text',
						'inputIsRequired' => true,
						'inputValue' => $this->getSettingsValue(self::SETTINGS_MAILER_SENDER_NAME_KEY, $formId),
					],
					[
						'component' => 'input',
						'inputName' => $this->getSettingsName(self::SETTINGS_MAILER_SENDER_EMAIL_KEY),
						'inputId' => $this->getSettingsName(self::SETTINGS_MAILER_SENDER_EMAIL_KEY),
						'inputFieldLabel' => \__('Sender e-mail', 'seb-forms'),
						'inputFieldHelp' => \__('Shows in the e-mail client as <i>From:</i>', 'seb-forms'),
						'inputType' => 'text',
						'inputIsEmail' => true,
						'inputIsRequired' => true,
						'inputValue' => $this->getSettingsValue(self::SETTINGS_MAILER_SENDER_EMAIL_KEY, $formId),
					],
					[
						'component' => 'divider',
					],
					[
						'component' => 'input',
						'inputName' => $this->getSettingsName(self::SETTINGS_MAILER_TO_KEY),
						'inputId' => $this->getSettingsName(self::SETTINGS_MAILER_TO_KEY),
						'inputFieldLabel' => \__('E-mail destination', 'seb-forms'),
						// translators: %s will be replaced with forms field name.
						'inputFieldHelp' => \sprintf(\__('The e-mail will be sent to this address.
						<br /> <br />
						Data from the form can be used in the form of template tags (<code>{field-name}</code>).
						<br />
						<b>WARNING: Be careful when using template tags and make sure that tag you are using contains a valid email address value.</b>', 'seb-forms'), $formNames),
						'inputType' => 'text',
						'inputIsRequired' => true,
						'inputValue' => $this->getSettingsValue(self::SETTINGS_MAILER_TO_KEY, $formId),
					],
					[
						'component' => 'input',
						'inputName' => $this->getSettingsName(self::SETTINGS_MAILER_SUBJECT_KEY),
						'inputId' => $this->getSettingsName(self::SETTINGS_MAILER_SUBJECT_KEY),
						'inputFieldLabel' => \__('E-mail subject', 'seb-forms'),
						// translators: %s will be replaced with forms field name.
						'inputFieldHelp' => \sprintf(\__('Data from the form can be used in the form of template tags (<code>{field-name}</code>).', 'seb-forms'), $formNames),
						'inputType' => 'text',
						'inputIsRequired' => true,
						'inputValue' => $this->getSettingsValue(self::SETTINGS_MAILER_SUBJECT_KEY, $formId),
					],
					[
						'component' => 'textarea',
						'textareaName' => $this->getSettingsName(self::SETTINGS_MAILER_TEMPLATE_KEY),
						'textareaId' => $this->getSettingsName(self::SETTINGS_MAILER_TEMPLATE_KEY),
						'textareaFieldLabel' => \__('E-mail content', 'seb-forms'),
						// translators: %s will be replaced with forms field name.
						'textareaFieldHelp' => \sprintf(\__('Data from the form can be used in the form of template tags (<code>{field-name}</code>).
							<br /> <br />
							These tags are detected from the form:
							<br />
							%s
							<br /> <br />
							If some tags are missing or you don\'t see any tags above, check that the <code>name</code> on the form field is set in the Form editor.', 'seb-forms'), $formNames),
						'textareaIsRequired' => true,
						'textareaValue' => $this->getSettingsValue(self::SETTINGS_MAILER_TEMPLATE_KEY, $formId),
					],
					[
						'component' => 'divider',
					],
					[
						'component' => 'intro',
						'introTitle' => \__('Confirmation mail', 'seb-forms'),
						'introTitleSize' => 'medium',
						'introSubtitle' => \__('The confirmation mail is sent to the user that filled in the form, usually a "thank you" e-mail or similar.
							<br /> <br />
							Leave blank to disable the confirmation e-mail.', 'seb-forms'),
					],
					[
						'component' => 'input',
						'inputName' => $this->getSettingsName(self::SETTINGS_MAILER_SENDER_SUBJECT_KEY),
						'inputId' => $this->getSettingsName(self::SETTINGS_MAILER_SENDER_SUBJECT_KEY),
						'inputFieldLabel' => \__('E-mail subject', 'seb-forms'),
						// translators: %s will be replaced with forms field name.
						'inputFieldHelp' => \sprintf(\__('Data from the form can be used in the form of template tags (<code>{field-name}</code>).', 'seb-forms'), $formNames),
						'inputType' => 'text',
						'inputIsRequired' => false,
						'inputValue' => $this->getSettingsValue(self::SETTINGS_MAILER_SENDER_SUBJECT_KEY, $formId),
					],
					[
						'component' => 'textarea',
						'textareaName' => $this->getSettingsName(self::SETTINGS_MAILER_SENDER_TEMPLATE_KEY),
						'textareaId' => $this->getSettingsName(self::SETTINGS_MAILER_SENDER_TEMPLATE_KEY),
						'textareaFieldLabel' => \__('E-mail content', 'seb-forms'),
						// translators: %s will be replaced with forms field name.
						'textareaFieldHelp' => \sprintf(\__('Data from the form can be used in the form of template tags (<code>{field-name}</code>).
							<br /> <br />
							These tags are detected from the form:
							<br />
							%s
							<br /> <br />
							If some tags are missing or you don\'t see any tags above, check that the <code>name</code> on the form field is set in the Form editor.
						', 'seb-forms'), $formNames),
						'textareaIsRequired' => false,
						'textareaValue' => $this->getSettingsValue(self::SETTINGS_MAILER_SENDER_TEMPLATE_KEY, $formId),
					],
				]
			);
		}

		return $output;
	}
}
