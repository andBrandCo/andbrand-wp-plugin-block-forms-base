<?php

namespace Tests\Unit\Integrations\Hubspot;

use Brain\Monkey;
use SebFormsWpPlugin\Integrations\Clearbit\ClearbitClient;
use SebFormsWpPlugin\Integrations\Clearbit\ClearbitClientInterface;
use SebFormsWpPlugin\Integrations\Clearbit\SettingsClearbit;
use SebFormsWpPlugin\Integrations\Clearbit\SettingsClearbitDataInterface;
use SebFormsWpPlugin\Integrations\Hubspot\Hubspot;
use SebFormsWpPlugin\Integrations\Hubspot\HubspotClient;
use SebFormsWpPlugin\Integrations\Hubspot\HubspotClientInterface;
use SebFormsWpPlugin\Integrations\Hubspot\SettingsHubspot;
use SebFormsWpPlugin\Integrations\MapperInterface;
use SebFormsWpPlugin\Labels\Labels;
use SebFormsWpPlugin\Validation\Validator;

use function Tests\setupMocks;

class SettingsHubspotMock extends SettingsHubspot {

	public function __construct(
		ClearbitClientInterface $clearbitClient,
		SettingsClearbitDataInterface $clearbitSettings,
		HubspotClientInterface $hubspotClient,
		MapperInterface $hubspot
	) {
		parent::__construct($clearbitClient, $clearbitSettings, $hubspotClient, $hubspot);
	}
};

/**
 * Mock before tests.
 */
beforeEach(function () {
	Monkey\setUp();
	setupMocks();

	$hubspotClient = new HubspotClient();
	$labels = new Labels();
	$validator = new Validator($labels);
	$clearbitClient = new ClearbitClient();
	$clearbitSettings = new SettingsClearbit($clearbitClient);

	$hubspot = new Hubspot($hubspotClient, $validator);

	$this->hubspotSettings = new SettingsHubspotMock($clearbitClient, $clearbitSettings, $hubspotClient, $hubspot);
});

afterAll(function() {
	Monkey\tearDown();
});

test('Register method will call sidebar hook', function () {
	$this->hubspotSettings->register();

	$this->assertSame(10, \has_filter(SettingsHubspotMock::FILTER_SETTINGS_SIDEBAR_NAME, 'Tests\Unit\Integrations\Hubspot\SettingsHubspotMock->getSettingsSidebar()'), 'The callback getSettingsSidebar should be hooked to custom filter hook with priority 10.');
	$this->assertSame(10, \has_filter(SettingsHubspotMock::FILTER_SETTINGS_NAME, 'Tests\Unit\Integrations\Hubspot\SettingsHubspotMock->getSettingsData()'), 'The callback getSettingsData should be hooked to custom filter hook with priority 10.');
	$this->assertSame(10, \has_filter(SettingsHubspotMock::FILTER_SETTINGS_GLOBAL_NAME, 'Tests\Unit\Integrations\Hubspot\SettingsHubspotMock->getSettingsGlobalData()'), 'The callback getSettingsGlobalData should be hooked to custom filter hook with priority 10.');
	$this->assertSame(10, \has_filter(SettingsHubspotMock::FILTER_SETTINGS_IS_VALID_NAME, 'Tests\Unit\Integrations\Hubspot\SettingsHubspotMock->isSettingsValid()'), 'The callback isSettingsValid should be hooked to custom filter hook with priority 10.');
});
