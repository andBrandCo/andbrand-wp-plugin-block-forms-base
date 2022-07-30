<?php

namespace Tests\Unit\Login;

use Brain\Monkey;
use AndbrandWpPluginBlockFormsBase\Main\Main;

use function Tests\setupMocks;

beforeEach(function() {
	Monkey\setUp();
	setupMocks();

	$this->main = new Main([], '');
});

afterEach(function() {
	Monkey\tearDown();
});

test('Register method will call init hook', function () {
	$this->main->register();

	$this->assertSame(10, has_action('plugins_loaded', 'AndbrandWpPluginBlockFormsBase\Main\Main->registerServices()'));
});
