<?php

/**
 * Class that holds all labels.
 *
 * @package EightshiftLibs\Labels
 */

declare(strict_types=1);

namespace EightshiftForms\Labels;

use EightshiftForms\Integrations\Goodbits\SettingsGoodbits;
use EightshiftForms\Integrations\Greenhouse\SettingsGreenhouse;
use EightshiftForms\Integrations\Hubspot\SettingsHubspot;
use EightshiftForms\Integrations\Mailchimp\SettingsMailchimp;
use EightshiftForms\Integrations\Mailerlite\SettingsMailerlite;
use EightshiftForms\Settings\SettingsHelper;
use EightshiftForms\Validation\SettingsCaptcha;

/**
 * Labels class.
 */
class Labels implements LabelsInterface
{
	/**
	 * Use general helper trait.
	 */
	use SettingsHelper;

	/**
	 * List all label keys that are stored in local form everything else is global settings.
	 */
	public const ALL_LOCAL_LABELS = [
		'mailerSuccess',
		'greenhouseSuccess',
		'mailchimpSuccess',
		'hubspotSuccess',
		'mailerliteSuccess',
		'goodbitsSuccess',
	];

	/**
	 * Get all labels
	 *
	 * @return array<string, string>
	 */
	public function getLabels(): array
	{
		$output = array_merge(
			$this->getGenericLabels(),
			$this->getValidationLabels(),
			$this->getMailerLabels()
		);

		// Google reCaptcha.
		if ($this->isCheckboxOptionChecked(SettingsCaptcha::SETTINGS_CAPTCHA_USE_KEY, SettingsCaptcha::SETTINGS_CAPTCHA_USE_KEY)) {
			$output = array_merge($output, $this->getCaptchaLabels());
		}

		// Greenhouse.
		if ($this->isCheckboxOptionChecked(SettingsGreenhouse::SETTINGS_GREENHOUSE_USE_KEY, SettingsGreenhouse::SETTINGS_GREENHOUSE_USE_KEY)) {
			$output = array_merge($output, $this->getGreenhouseLabels());
		}

		// Mailchimp.
		if ($this->isCheckboxOptionChecked(SettingsMailchimp::SETTINGS_MAILCHIMP_USE_KEY, SettingsMailchimp::SETTINGS_MAILCHIMP_USE_KEY)) {
			$output = array_merge($output, $this->getMailchimpLabels());
		}

		// Hubspot.
		if ($this->isCheckboxOptionChecked(SettingsHubspot::SETTINGS_HUBSPOT_USE_KEY, SettingsHubspot::SETTINGS_HUBSPOT_USE_KEY)) {
			$output = array_merge($output, $this->getHubspotLabels());
		}

		// Mailerlite.
		if ($this->isCheckboxOptionChecked(SettingsMailerlite::SETTINGS_MAILERLITE_USE_KEY, SettingsMailerlite::SETTINGS_MAILERLITE_USE_KEY)) {
			$output = array_merge($output, $this->getMailerliteLabels());
		}

		// Goodbits.
		if ($this->isCheckboxOptionChecked(SettingsGoodbits::SETTINGS_GOODBITS_USE_KEY, SettingsGoodbits::SETTINGS_GOODBITS_USE_KEY)) {
			$output = array_merge($output, $this->getGoodbitsLabels());
		}

		return $output;
	}

	/**
	 * Return labels - Generic
	 *
	 * @return array<string, string>
	 */
	private function getGenericLabels(): array
	{
		return [
			'submitWpError' => __('Something went wrong while submitting your form. Please try again.', 'eightshift-forms'),
		];
	}

	/**
	 * Return labels - Validation
	 *
	 * @return array<string, string>
	 */
	private function getValidationLabels(): array
	{
		return [
			'validationRequired' => __('This field is required.', 'eightshift-forms'),
			// translators: %s used for displaying required number.
			'validationRequiredCount' => __('This field is required, with at least %s items selected.', 'eightshift-forms'),
			'validationEmail' => __('This e-mails is not valid.', 'eightshift-forms'),
			'validationUrl' => __('This URL is not valid.', 'eightshift-forms'),
			// translators: %s used for displaying length min number to the user.
			'validationMinLength' => __('This field value has less characters than expected. We expect minimum %s characters.', 'eightshift-forms'),
			// translators: %s used for displaying length max number to the user.
			'validationMaxLength' => __('This field value has more characters than expected. We expect maximum %s characters.', 'eightshift-forms'),
			'validationNumber' => __('This field should only contain numbers.', 'eightshift-forms'),
			// translators: %s used for displaying validation pattern to the user.
			'validationPattern' => __('This field doesn\'t satisfy the validation pattern: %s.', 'eightshift-forms'),
			// translators: %s used for displaying file type value.
			'validationAccept' => __('The file type is not supported. Only %s are allowed.', 'eightshift-forms'),
			// translators: %s used for displaying number value.
			'validationMinSize' => __('The file is smaller than allowed. Minimum file size is %s kB.', 'eightshift-forms'),
			// translators: %s used for displaying number value.
			'validationMaxSize' => __('The file is larger than allowed. Maximum file size is %s kB.', 'eightshift-forms'),
		];
	}

	/**
	 * Return labels - Mailer
	 *
	 * @return array<string, string>
	 */
	private function getMailerLabels(): array
	{
		return [
			'mailerSuccessNoSend' => __('E-mail was sent successfully.', 'eightshift-forms'),
			'mailerErrorSettingsMissing' => __('Form settings are not configured correctly. Please try again.', 'eightshift-forms'),
			'mailerErrorEmailSend' => __('E-mail was not sent due to an unknown issue. Please try again.', 'eightshift-forms'),
			'mailerErrorEmailConfirmationSend' => __('Confirmation e-mail was not sent due to unknown issue. Please try again.', 'eightshift-forms'),
			'mailerSuccess' => __('E-mail was sent successfully.', 'eightshift-forms'),
		];
	}

	/**
	 * Return labels - Greenhouse
	 *
	 * @return array<string, string>
	 */
	private function getGreenhouseLabels(): array
	{
		return [
			'greenhouseErrorSettingsMissing' => __('Greenhouse integration is not configured correctly. Please try again.', 'eightshift-forms'),
			'greenhouseBadRequestError' => __('Something is not right with the job application. Please check all the fields and try again.', 'eightshift-forms'),
			'greenhouseUnsupportedFileTypeError' => __('An unsupported file type was uploaded. Please try again.', 'eightshift-forms'),
			'greenhouseInvalidFirstNameError' => __('"First name" is in an incorrect format.', 'eightshift-forms'),
			'greenhouseInvalidLastNameError' => __('"Last name" is in an incorrect format.', 'eightshift-forms'),
			'greenhouseInvalidEmailError' => __('"E-mail" is in an incorrect format.', 'eightshift-forms'),
			'greenhouseInvalidFirstNameLastNameEmailError' => __('"First name", "Last name", and "E-mail" are in an incorrect format.', 'eightshift-forms'),
			'greenhouseInvalidFirstNameLastNameError' => __('"First name" and "Last name" are in an incorrect format.', 'eightshift-forms'),
			'greenhouseInvalidFirstNameEmailError' => __('"First name" and "E-mail" are in an incorrect format.', 'eightshift-forms'),
			'greenhouseInvalidLastNameEmailError' => __('"Last name" and "E-mail" are in an incorrect format.', 'eightshift-forms'),
			'greenhouseInvalidFirstNamePhoneError' => __('"First name" and "Phone" are an incorrect format.', 'eightshift-forms'),
			'greenhouseInvalidLastNamePhoneError' => __('"First name" and "Phone" are in an incorrect format.', 'eightshift-forms'),
			'greenhouseInvalidEmailPhoneError' => __('"E-mail" and "Phone" are in an incorrect format.', 'eightshift-forms'),
			'greenhouseInvalidFirstNameLastNameEmailPhoneError' => __('"First name", "Last name", "E-mail", and "Phone" are in an incorrect format.', 'eightshift-forms'),
			'greenhouseInvalidFirstNameLastNamePhoneError' => __('"First name", "Last name", and "Phone" are in an incorrect format.', 'eightshift-forms'),
			'greenhouseInvalidFirstNameEmailPhoneError' => __('"First name", "E-mail", and "Phone" are in an incorrect format.', 'eightshift-forms'),
			'greenhouseInvalidLastNameEmailPhoneError' => __('"Last name", "E-mail", and "Phone" are in an incorrect format.', 'eightshift-forms'),
			'greenhouseSuccess' => __('Application submitted successfully. Thank you!', 'eightshift-forms'),
		];
	}

	/**
	 * Return labels - Mailchimp
	 *
	 * @return array<string, string>
	 */
	private function getMailchimpLabels(): array
	{
		return [
			'mailchimpErrorSettingsMissing' => __('Mailchimp integration is not configured correctly. Please try again.', 'eightshift-forms'),
			'mailchimpBadRequestError' => __('Something is not right with the subscription. Please check all the fields and try again.', 'eightshift-forms'),
			'mailchimpInvalidResourceError' => __('Something is not right with the resource. Please check all the fields and try again.', 'eightshift-forms'),
			'mailchimpInvalidEmailError' => __('"E-mail" is in an incorrect format.', 'eightshift-forms'),
			'mailchimpMissingFieldsError' => __('It looks like some required fields are missing. Please check all the fields and try again.', 'eightshift-forms'),
			'mailchimpSuccess' => __('Newsletter subscription successful. Thank you!', 'eightshift-forms'),
		];
	}

	/**
	 * Return labels - HubSpot
	 *
	 * @return array<string, string>
	 */
	private function getHubspotLabels(): array
	{
		return [
			'hubspotErrorSettingsMissing' => __('Hubspot integration is not configured correctly. Please try again.', 'eightshift-forms'),
			'hubspotBadRequestError' => __('Something is not with the application. Please check all the fields and try again.', 'eightshift-forms'),
			'hubspotInvalidRequestError' => __('Something is not right with the application. Please check all the fields and try again.', 'eightshift-forms'),
			'hubspotInvalidEmailError' => __('"E-mail" is in an incorrect format.', 'eightshift-forms'),
			'hubspotMissingFieldsError' => __('Some required fields are not filled in, please check them and try again.', 'eightshift-forms'),
			'hubspotSuccess' => __('The form was submitted successfully. Thank you!', 'eightshift-forms'),
		];
	}

	/**
	 * Return labels - Mailerlite
	 *
	 * @return array<string, string>
	 */
	private function getMailerliteLabels(): array
	{
		return [
			'mailerliteErrorSettingsMissing' => __('MailerLite integration is not configured correctly. Please try again.', 'eightshift-forms'),
			'mailerliteBadRequestError' => __('Something is not right with the subscription. Please check all the fields and try again.', 'eightshift-forms'),
			'mailerliteInvalidEmailError' => __('"E-mail" is in an incorrect format.', 'eightshift-forms'),
			'mailerliteEmailTemporarilyBlockedError' => __('The e-mail is temporarily blocked by our e-mail client. Please try again later or use try a different e-mail.', 'eightshift-forms'),
			'mailerliteSuccess' => __('The newsletter was successful. Thank you!', 'eightshift-forms'),
		];
	}

	/**
	 * Return labels - Goodbits
	 *
	 * @return array<string, string>
	 */
	private function getGoodbitsLabels(): array
	{
		return [
			'goodbitsErrorSettingsMissing' => __('Goodbits integration is not configured correctly. Please try again.', 'eightshift-forms'),
			'goodbitsBadRequestError' => __('Something is not right with the subscription. Please check all the fields and try again.', 'eightshift-forms'),
			'goodbitsInvalidEmailError' => __('"E-mail" is in an incorrect format.', 'eightshift-forms'),
			'goodbitsUnauthorizedError' => __('There was an authorization error (incorrect API key). Contact support.', 'eightshift-forms'),
			'goodbitsSuccess' => __('The newsletter subscription was successful. Thank you!', 'eightshift-forms'),
		];
	}

	/**
	 * Return labels - Google reCaptcha
	 *
	 * @return array<string, string>
	 */
	private function getCaptchaLabels(): array
	{
		return [
			'captchaMissingInputSecret' => __('The Captcha "secret" parameter is missing.', 'eightshift-forms'),
			'captchaInvalidInputSecret' => __('The Captcha "secret" parameter is invalid or malformed.', 'eightshift-forms'),
			'captchaInvalidInputResponse' => __('The Captcha "response" parameter is invalid or malformed.', 'eightshift-forms'),
			'captchaMissingInputResponse' => __('The Captcha "response" parameter is missing.', 'eightshift-forms'),
			'captchaBadRequest' => __('The Captcha "request" is invalid or malformed.', 'eightshift-forms'),
			'captchaTimeoutOrDuplicate' => __('The Captcha response is no longer valid: either is too old or has been used previously.', 'eightshift-forms'),
			'captchaWrongAction' => __('The Captcha response "action" is not valid.', 'eightshift-forms'),
			'captchaIncorrectCaptchaSol' => __('The Captcha keys are not valid. Please check your site and secret key configuration.', 'eightshift-forms'),
			'captchaScoreSpam' => __('The automated system detected this request as a potential spam request. Please try again.', 'eightshift-forms'),
		];
	}

	/**
	 * Return one label by key
	 *
	 * @param string $key Label key.
	 * @param string $formId Form ID.
	 *
	 * @return string
	 */
	public function getLabel(string $key, string $formId = ''): string
	{
		// If form ID is not missing check form settings for the overrides.
		if (!empty($formId)) {
			$local = array_flip(self::ALL_LOCAL_LABELS);

			if (isset($local[$key])) {
				$dbLabel = $this->getSettingsValue($key, $formId);
			} else {
				$dbLabel = $this->getOptionValue($key);
			}

			// If there is an override in the DB use that.
			if (!empty($dbLabel)) {
				return $dbLabel;
			}
		}

		$labels = $this->getLabels();

		return $labels[$key] ?? '';
	}
}
