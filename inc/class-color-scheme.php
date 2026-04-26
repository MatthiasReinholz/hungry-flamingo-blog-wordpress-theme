<?php
/**
 * Color scheme (light/dark) cookie bridge.
 *
 * The actual toggle happens client-side (see assets/js/color-scheme.js).
 * This module only reads the first-party preference cookie so the initial HTML
 * can paint in the correct scheme before JavaScript runs.
 *
 * @package HFB
 */

declare( strict_types = 1 );

namespace HFB;

defined( 'ABSPATH' ) || exit;

final class Color_Scheme {

	private const COOKIE_NAME = 'hfb_theme';

	public function register(): void {
		add_filter( 'body_class', [ $this, 'body_class' ] );
		add_filter( 'language_attributes', [ $this, 'html_attributes' ], 10, 1 );
	}

	/**
	 * Add a has-scheme body class so CSS can target per-scheme if ever needed in
	 * addition to the data-theme attribute on <html>.
	 *
	 * @param string[] $classes
	 * @return string[]
	 */
	public function body_class( array $classes ): array {
		$classes[] = 'hfb-has-color-scheme';
		return $classes;
	}

	/**
	 * Echo the initial scheme onto <html> via the language_attributes filter, so
	 * that even with JS disabled we render the user's last choice.
	 */
	public function html_attributes( string $output ): string {
		$cookie = isset( $_COOKIE[ self::COOKIE_NAME ] )
			? sanitize_key( wp_unslash( $_COOKIE[ self::COOKIE_NAME ] ) )
			: '';
		$default = defined( 'HFB_DEFAULT_SCHEME' ) ? HFB_DEFAULT_SCHEME : 'light';
		$scheme  = in_array( $cookie, [ 'light', 'dark' ], true ) ? $cookie : $default;
		return $output . ' data-theme="' . esc_attr( $scheme ) . '"';
	}
}
