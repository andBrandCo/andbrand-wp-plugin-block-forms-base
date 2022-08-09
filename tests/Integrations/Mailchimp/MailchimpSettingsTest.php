<?php

namespace Tests\Unit\Integrations\Mailchimp;

use Brain\Monkey;
use SebFormsWpPlugin\Integrations\Mailchimp\Mailchimp;
use SebFormsWpPlugin\Integrations\Mailchimp\MailchimpClient;
use SebFormsWpPlugin\Integrations\Mailchimp\MailchimpClientInterface;
use SebFormsWpPlugin\Integrations\Mailchimp\SettingsMailchimp;
use SebFormsWpPlugin\Integrations\MapperInterface;
use SebFormsWpPlugin\Labels\Labels;
use SebFormsWpPlugin\Validation\Validator;

use function Tests\setupMocks;

class SettingsMailchimpMock extends SettingsMailchimp {

	public function __construct(
		MailchimpClientInterface $mailchimpClient,
		MapperInterface $mailchimp
	) {
		parent::__construct($mailchimpClient, $mailchimp);
	}
};

/**
 * Mock before tests.
 */
beforeEach(function () {
	Monkey\setUp();
	setupMocks();

	$mailchimpClient = new MailchimpClient();
	$labels = new Labels();
	$validator = new Validator($labels);
	$mailchimp = new Mailchimp($mailchimpClient, $validator);

	$this->mailchimpSettings = new SettingsMailchimpMock($mailchimpClient, $mailchimp);
});

afterAll(function() {
	Monkey\tearDown();
});

test('Register method will call sidebar hook', function () {
	$this->mailchimpSettings->register();

	$this->assertSame(10, \has_filter(SettingsMailchimpMock::FILTER_SETTINGS_SIDEBAR_NAME, 'Tests\Unit\Integrations\Mailchimp\SettingsMailchimpMock->getSettingsSidebar()'), 'The callback getSettingsSidebar should be hooked to custom filter hook with priority 10.');
	$this->assertSame(10, \has_filter(SettingsMailchimpMock::FILTER_SETTINGS_NAME, 'Tests\Unit\Integrations\Mailchimp\SettingsMailchimpMock->getSettingsData()'), 'The callback getSettingsData should be hooked to custom filter hook with priority 10.');
	$this->assertSame(10, \has_filter(SettingsMailchimpMock::FILTER_SETTINGS_GLOBAL_NAME, 'Tests\Unit\Integrations\Mailchimp\SettingsMailchimpMock->getSettingsGlobalData()'), 'The callback getSettingsGlobalData should be hooked to custom filter hook with priority 10.');
	$this->assertSame(10, \has_filter(SettingsMailchimpMock::FILTER_SETTINGS_IS_VALID_NAME, 'Tests\Unit\Integrations\Mailchimp\SettingsMailchimpMock->isSettingsValid()'), 'The callback isSettingsValid should be hooked to custom filter hook with priority 10.');
});
