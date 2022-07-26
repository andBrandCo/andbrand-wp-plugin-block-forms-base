<?php

namespace Tests\Unit\Integrations\Goodbits;

use Brain\Monkey;
use AndbrandWpPluginBlockFormsBase\Integrations\ClientInterface;
use AndbrandWpPluginBlockFormsBase\Integrations\Goodbits\Goodbits;
use AndbrandWpPluginBlockFormsBase\Integrations\Goodbits\GoodbitsClient;
use AndbrandWpPluginBlockFormsBase\Integrations\Goodbits\SettingsGoodbits;
use AndbrandWpPluginBlockFormsBase\Integrations\MapperInterface;
use AndbrandWpPluginBlockFormsBase\Labels\Labels;
use AndbrandWpPluginBlockFormsBase\Validation\Validator;

use function Tests\setupMocks;

class SettingsGoodbitsMock extends SettingsGoodbits {

	public function __construct(
		ClientInterface $goodbitsClient,
		MapperInterface $goodbits
	) {
		parent::__construct($goodbitsClient, $goodbits);
	}
};

/**
 * Mock before tests.
 */
beforeEach(function () {
	Monkey\setUp();
	setupMocks();

	$goodbitsClient = new GoodbitsClient();
	$labels = new Labels();
	$validator = new Validator($labels);
	$goodbits = new Goodbits($goodbitsClient, $validator);

	$this->goodbitsSettings = new SettingsGoodbitsMock($goodbitsClient, $goodbits);
});

afterAll(function() {
	Monkey\tearDown();
});

test('Register method will call sidebar hook', function () {
	$this->goodbitsSettings->register();

	$this->assertSame(10, \has_filter(SettingsGoodbitsMock::FILTER_SETTINGS_SIDEBAR_NAME, 'Tests\Unit\Integrations\Goodbits\SettingsGoodbitsMock->getSettingsSidebar()'), 'The callback getSettingsSidebar should be hooked to custom filter hook with priority 10.');
	$this->assertSame(10, \has_filter(SettingsGoodbitsMock::FILTER_SETTINGS_NAME, 'Tests\Unit\Integrations\Goodbits\SettingsGoodbitsMock->getSettingsData()'), 'The callback getSettingsData should be hooked to custom filter hook with priority 10.');
	$this->assertSame(10, \has_filter(SettingsGoodbitsMock::FILTER_SETTINGS_GLOBAL_NAME, 'Tests\Unit\Integrations\Goodbits\SettingsGoodbitsMock->getSettingsGlobalData()'), 'The callback getSettingsGlobalData should be hooked to custom filter hook with priority 10.');
	$this->assertSame(10, \has_filter(SettingsGoodbitsMock::FILTER_SETTINGS_IS_VALID_NAME, 'Tests\Unit\Integrations\Goodbits\SettingsGoodbitsMock->isSettingsValid()'), 'The callback isSettingsValid should be hooked to custom filter hook with priority 10.');
});
