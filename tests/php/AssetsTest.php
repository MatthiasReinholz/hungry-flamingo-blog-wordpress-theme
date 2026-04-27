<?php
/**
 * Assets tests.
 *
 * @package HFB
 */

declare( strict_types = 1 );

use HFB\Assets;
use PHPUnit\Framework\TestCase;

final class AssetsTest extends TestCase {

	protected function setUp(): void {
		parent::setUp();
		$GLOBALS['hfb_test_options']    = [ 'page_for_posts' => 42 ];
		$GLOBALS['hfb_test_permalinks'] = [ 42 => 'https://example.test/articles/' ];
	}

	protected function tearDown(): void {
		unset( $_COOKIE['hfb_theme'], $GLOBALS['hfb_test_options'], $GLOBALS['hfb_test_permalinks'] );
		parent::tearDown();
	}

	public function test_hydrates_links_independent_of_attribute_order(): void {
		$assets = new Assets();
		$html   = '<a data-hfb-home-link href="/">Articles</a>'
			. '<a data-hfb-search-link href="/?s=">Search</a>'
			. '<a data-hfb-feed-link href="/feed/">RSS</a>'
			. '<form data-hfb-search-form action="/"></form>';

		$hydrated = $assets->hydrate_dynamic_links( $html );

		$this->assertStringContainsString( 'href="https://example.test/articles/"', $hydrated );
		$this->assertStringContainsString( 'href="https://example.test/?s="', $hydrated );
		$this->assertStringContainsString( 'href="https://example.test/feed/"', $hydrated );
		$this->assertStringContainsString( 'action="https://example.test/"', $hydrated );
	}

	public function test_print_color_scheme_boot_uses_script_tag_helper(): void {
		$_COOKIE['hfb_theme'] = 'dark';

		ob_start();
		( new Assets() )->print_color_scheme_boot();
		$output = (string) ob_get_clean();

		$this->assertStringStartsWith( '<script id="hfb-color-scheme-boot">', $output );
		$this->assertStringContainsString( 'document.documentElement.setAttribute("data-theme"', $output );
	}
}
