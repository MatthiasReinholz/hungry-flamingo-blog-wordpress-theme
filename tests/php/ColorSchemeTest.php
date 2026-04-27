<?php
/**
 * Color scheme tests.
 *
 * @package HFB
 */

declare( strict_types = 1 );

use HFB\Color_Scheme;
use PHPUnit\Framework\TestCase;

final class ColorSchemeTest extends TestCase {

	protected function tearDown(): void {
		unset( $_COOKIE['hfb_theme'] );
		parent::tearDown();
	}

	public function test_resolves_valid_cookie(): void {
		$_COOKIE['hfb_theme'] = 'dark';

		$this->assertSame( 'dark', Color_Scheme::resolve_from_cookie() );
		$this->assertSame( 'en-US data-theme="dark"', ( new Color_Scheme() )->html_attributes( 'en-US' ) );
	}

	public function test_invalid_cookie_falls_back_to_default(): void {
		$_COOKIE['hfb_theme'] = 'javascript:alert(1)';

		$this->assertSame( 'light', Color_Scheme::resolve_from_cookie() );
	}

	public function test_body_class_marks_color_scheme_support(): void {
		$this->assertSame( [ 'site', 'hfb-has-color-scheme' ], ( new Color_Scheme() )->body_class( [ 'site' ] ) );
	}
}
