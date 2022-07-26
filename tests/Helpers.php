<?php

namespace Tests;

use Brain\Monkey\Functions;
use Exception;
use Mockery;
use Mockery\MockInterface;

/**
 * Helper function that will setup some repeating mocks in every tests.
 *
 * This is a way to circumvent the issue I was having described here:
 * https://github.com/pestphp/pest/issues/259
 */
function setupMocks() {
	// Mock WP functions
	Functions\stubTranslationFunctions();
	Functions\stubEscapeFunctions();

	if (!defined('DAY_IN_SECONDS')) {
		define('DAY_IN_SECONDS', 3600);
	}

	Functions\when('get_locale')->justReturn('HR');

	Functions\when('get_option')->alias(function (string $option, $default = false) {
		$envValue = getenv("test_force_option_{$option}");

		if ($envValue === false) {
			return $option;
		}

		if ($envValue === 'unset') {
			return $default;
		}

		return $envValue;
	});

	Functions\when('get_stylesheet_directory')->justReturn(dirname(__FILE__) . '/');

	Functions\when('get_permalink')->justReturn('');

	Functions\when('get_template_directory')->justReturn(dirname(__FILE__) . '/data');

	Functions\when('get_edit_post_link')->alias(function($id) {
		$url = "/wp-admin/post.php?post={$id}&action=edit";

		$envValue = getenv("test_force_admin_url_prefix");
		if ($envValue !== false) {
			$url = "/{$envValue}{$url}";
		}
		return $url;
	});

	Functions\when('get_delete_post_link')->alias(function ($id = 0, $deprecated = '', $force_delete = false) {
		if (!empty($deprecated)) {
			throw new Exception('Deprecated argument used in get_delete_post_link call');
		}

		$force_delete = $force_delete ? 'true' : 'false';
		return "id: {$id}, force: {$force_delete}";
	});

	Functions\when('get_the_content')->alias(
		function ($more_link_text = null, bool $strip_teaser = false, $post = null)
		{
			$posts = [
				[
					'post_content' => 'aa',
				],
				[
					'post_content' => '{"AndbrandWpPluginBlockFormsBaseInputFieldName":"myFirstField"}, {"anotherFieldName":"someField"}',
				]
			];

			return $posts[$post]['post_content'] ?? '';
		}
	);

	Functions\when('wp_nonce_url')->alias(function ($actionurl, $action = -1, $name = '_wpnonce') {
		return "{$actionurl}&{$name}={$action}";
	});

	Functions\when('wp_salt')->alias(function (string $scheme = 'auth') {
		return $scheme;
	});

	Functions\when('wp_get_mime_types')->alias(function () {
		return [
			'jpg|jpeg|jpe' => 'image/jpeg',
			'pdf' => 'application/pdf',
		];
	});

	Functions\when('wp_mail')->alias(function ($to, $subject, $message, $headers = '', $attachments = []) {
		$mail = json_encode([
			'to' => $to,
			'subject' => $subject,
			'message' => $message,
			'headers' => $headers,
			'attachments' => $attachments,
		]);

		putenv("test_wp_mail_last_call={$mail}");

		return (bool)filter_var($to, FILTER_VALIDATE_EMAIL);
	});

	Functions\when('get_post_meta')->alias(function ($post, $key = '', $single = false) {
		$envValue = getenv("test_force_post_meta_{$key}");
		if ($envValue === false) {
			return $key;
		}

		if ($envValue === 'unset') {
			return '';
		}

		return $envValue;
	});

	Functions\when('get_admin_url')->alias(function ($blog_id = null, string $path = '', string $scheme = 'admin') {
		$url = '/wp-admin/';
		$envValue = getenv("test_force_admin_url_prefix");
	
		if ($envValue !== false) {
			$url = "/{$envValue}{$url}";
		}

		if ($path && is_string($path)) {
			$url .= ltrim($path, '/');
		}

		return $url;
	});
}

function mock(string $class): MockInterface
{
	return Mockery::mock($class);
}
