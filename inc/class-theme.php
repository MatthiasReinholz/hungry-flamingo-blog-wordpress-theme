<?php
/**
 * Main theme bootstrap.
 *
 * Kept intentionally thin: its only job is to register theme supports and the
 * set of feature modules.
 *
 * @package HFB
 */

declare( strict_types = 1 );

namespace HFB;

defined( 'ABSPATH' ) || exit;

final class Theme {

	/**
	 * Feature modules. Each must expose a register(): void method.
	 *
	 * @var string[]
	 */
	private $modules = [
		Assets::class,
		Color_Scheme::class,
		WooCommerce::class,
	];

	public function boot(): void {
		$this->register_theme_supports();
		$this->register_modules();

		add_action( 'init', [ $this, 'register_pattern_category' ] );
	}

	public function register_pattern_category(): void {
		if ( function_exists( 'register_block_pattern_category' ) ) {
			register_block_pattern_category(
				'hungry-flamingo-blog',
				[ 'label' => __( 'Hungry Flamingo', 'hungry-flamingo-blog' ) ]
			);
		}
	}

	private function register_theme_supports(): void {
		load_theme_textdomain( 'hungry-flamingo-blog', HFB_DIR . 'languages' );

		add_theme_support( 'wp-block-styles' );
		add_theme_support( 'editor-styles' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'align-wide' );
		add_theme_support( 'custom-logo', [
			'width'       => 120,
			'height'      => 120,
			'flex-width'  => true,
			'flex-height' => true,
		] );
		add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ] );
		add_theme_support( 'post-formats', [ 'aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat' ] );

		add_editor_style( [ 'assets/css/theme.css', 'assets/css/woocommerce.css', 'assets/css/editor.css' ] );
	}

	private function register_modules(): void {
		foreach ( $this->modules as $module_class ) {
			if ( class_exists( $module_class ) ) {
				$instance = new $module_class();
				if ( method_exists( $instance, 'register' ) ) {
					$instance->register();
				}
			}
		}
	}
}
