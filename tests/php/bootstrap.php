<?php
/**
 * PHPUnit bootstrap with minimal WordPress stubs.
 *
 * @package HFB
 */

declare( strict_types = 1 );

defined( 'ABSPATH' ) || define( 'ABSPATH', __DIR__ . '/' );
defined( 'HFB_VERSION' ) || define( 'HFB_VERSION', '1.0.0' );
defined( 'HFB_DIR' ) || define( 'HFB_DIR', dirname( __DIR__, 2 ) . '/' );
defined( 'HFB_URI' ) || define( 'HFB_URI', 'https://example.test/wp-content/themes/hungry-flamingo-blog/' );
defined( 'HFB_DEFAULT_SCHEME' ) || define( 'HFB_DEFAULT_SCHEME', 'light' );

if ( ! class_exists( 'WooCommerce' ) ) {
	class WooCommerce {}
}

if ( ! class_exists( 'WP_Post' ) ) {
	class WP_Post {
		public string $post_content = '';
	}
}

if ( ! class_exists( 'WP_HTML_Tag_Processor' ) ) {
	class WP_HTML_Tag_Processor {
		private string $html;
		private int $offset = 0;
		private array $match = [];

		public function __construct( string $html ) {
			$this->html = $html;
		}

		public function next_tag(): bool {
			if ( ! preg_match( '/<([a-z0-9-]+)\\b([^>]*)>/i', $this->html, $match, PREG_OFFSET_CAPTURE, $this->offset ) ) {
				return false;
			}

			$this->match  = $match;
			$this->offset = $match[0][1] + strlen( $match[0][0] );
			return true;
		}

		public function get_attribute( string $name ) {
			$tag = $this->match[0][0] ?? '';
			if ( preg_match( '/\\s' . preg_quote( $name, '/' ) . '(?:=(["\\\'])(.*?)\\1)?/i', $tag, $match ) ) {
				return array_key_exists( 2, $match ) ? $match[2] : true;
			}

			return null;
		}

		public function set_attribute( string $name, string $value ): bool {
			$tag   = $this->match[0][0];
			$start = $this->match[0][1];
			$attr  = sprintf( '%s="%s"', $name, htmlspecialchars( $value, ENT_QUOTES ) );

			if ( preg_match( '/\\s' . preg_quote( $name, '/' ) . '(?:=(["\\\']).*?\\1)?/i', $tag ) ) {
				$new_tag = preg_replace( '/\\s' . preg_quote( $name, '/' ) . '(?:=(["\\\']).*?\\1)?/i', ' ' . $attr, $tag, 1 );
			} else {
				$new_tag = preg_replace( '/>$/', ' ' . $attr . '>', $tag );
			}

			$this->html   = substr_replace( $this->html, (string) $new_tag, $start, strlen( $tag ) );
			$this->offset = $start + strlen( (string) $new_tag );
			$this->match[0][0] = (string) $new_tag;
			return true;
		}

		public function get_updated_html(): string {
			return $this->html;
		}
	}
}

function add_action(): void {}
function add_filter(): void {}
function apply_filters( string $hook_name, $value ) {
	return $value;
}
function add_theme_support(): void {}
function load_theme_textdomain(): void {}
function add_editor_style(): void {}
function register_block_pattern_category(): void {}
function register_block_style(): void {}

function esc_url( string $value ): string {
	return htmlspecialchars( $value, ENT_QUOTES );
}

function esc_url_raw( string $value ): string {
	return $value;
}

function esc_attr( string $value ): string {
	return htmlspecialchars( $value, ENT_QUOTES );
}

function sanitize_key( string $key ): string {
	return preg_replace( '/[^a-z0-9_\\-]/', '', strtolower( $key ) ) ?? '';
}

function wp_unslash( $value ) {
	return is_string( $value ) ? stripslashes( $value ) : $value;
}

function wp_json_encode( $value ): string {
	return (string) json_encode( $value );
}

function wp_print_inline_script_tag( string $script, array $attributes = [] ): void {
	$id = isset( $attributes['id'] ) ? ' id="' . esc_attr( (string) $attributes['id'] ) . '"' : '';
	echo '<script' . $id . '>' . $script . '</script>';
}

function home_url( string $path = '/' ): string {
	return 'https://example.test' . $path;
}

function add_query_arg( string $key, string $value, string $url ): string {
	return $url . '?' . rawurlencode( $key ) . '=' . rawurlencode( $value );
}

function get_feed_link(): string {
	return 'https://example.test/feed/';
}

function get_bloginfo( string $show ): string {
	return 'name' === $show ? 'Test Site' : '';
}

function wp_strip_all_tags( string $value ): string {
	return strip_tags( $value );
}

function wp_enqueue_style(): void {}
function wp_enqueue_script(): void {}
function wp_localize_script(): void {}
function wp_enqueue_block_style(): void {}
function unregister_block_pattern( string $pattern_name ): void {
	$GLOBALS['hfb_unregistered_patterns'][] = $pattern_name;
}
function function_exists_stub(): bool { return true; }

function get_option( string $name ) {
	return $GLOBALS['hfb_test_options'][ $name ] ?? 0;
}

function get_permalink( int $post_id ) {
	return $GLOBALS['hfb_test_permalinks'][ $post_id ] ?? false;
}

function is_search(): bool {
	return (bool) ( $GLOBALS['hfb_is_search'] ?? false );
}

function get_query_var( string $name ) {
	return $GLOBALS['hfb_query_vars'][ $name ] ?? null;
}

function is_singular(): bool {
	return (bool) ( $GLOBALS['hfb_is_singular'] ?? false );
}

function get_post() {
	return $GLOBALS['hfb_current_post'] ?? null;
}

function has_block( string $block_name, $post ): bool {
	return $post instanceof WP_Post && false !== strpos( $post->post_content, '<!-- wp:' . $block_name );
}

require_once HFB_DIR . 'inc/class-asset-version.php';
require_once HFB_DIR . 'inc/class-assets.php';
require_once HFB_DIR . 'inc/class-color-scheme.php';
require_once HFB_DIR . 'inc/class-woocommerce.php';
