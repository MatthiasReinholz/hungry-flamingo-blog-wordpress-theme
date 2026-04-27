<?php
/**
 * WooCommerce compatibility layer.
 *
 * @package HFB
 */

declare( strict_types = 1 );

namespace HFB;

defined( 'ABSPATH' ) || exit;

final class WooCommerce {

	use Asset_Version;

	private const BLOCKS = [
		'woocommerce/product-collection',
		'woocommerce/product-template',
		'woocommerce/cart',
		'woocommerce/checkout',
		'woocommerce/mini-cart',
		'woocommerce/featured-product',
		'woocommerce/handpicked-products',
	];

	private const PATTERNS = [
		'hungry-flamingo-blog/woocommerce-cart-trust-band',
		'hungry-flamingo-blog/woocommerce-featured-product',
		'hungry-flamingo-blog/woocommerce-post-product-cta',
		'hungry-flamingo-blog/woocommerce-post-purchase-reading',
		'hungry-flamingo-blog/woocommerce-product-collection',
		'hungry-flamingo-blog/woocommerce-product-comparison',
	];

	public function register(): void {
		$this->register_theme_supports();

		add_action( 'init', [ $this, 'register_block_styles' ] );
		add_action( 'init', [ $this, 'maybe_unregister_patterns' ], 100 );
		add_filter( 'woocommerce_enqueue_styles', [ $this, 'filter_woocommerce_styles' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_styles' ] );
	}

	private function register_theme_supports(): void {
		add_theme_support(
			'woocommerce',
			[
				'thumbnail_image_width' => 480,
				'single_image_width'    => 760,
				'product_grid'          => [
					'default_rows'    => 4,
					'min_rows'        => 2,
					'max_rows'        => 8,
					'default_columns' => 3,
					'min_columns'     => 2,
					'max_columns'     => 4,
				],
			]
		);

		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
	}

	public function register_block_styles(): void {
		if ( ! function_exists( 'wp_enqueue_block_style' ) ) {
			return;
		}

		foreach ( self::BLOCKS as $block_name ) {
			wp_enqueue_block_style(
				$block_name,
				[
					'handle' => 'hfb-woocommerce',
					'src'    => HFB_URI . 'assets/css/woocommerce.css',
					'path'   => HFB_DIR . 'assets/css/woocommerce.css',
					'deps'   => [ 'hfb-theme' ],
					'ver'    => $this->asset_version( 'assets/css/woocommerce.css' ),
				]
			);
		}
	}

	public function maybe_unregister_patterns(): void {
		if ( class_exists( '\WooCommerce' ) || ! function_exists( 'unregister_block_pattern' ) ) {
			return;
		}

		foreach ( self::PATTERNS as $pattern_name ) {
			unregister_block_pattern( $pattern_name );
		}
	}

	public function enqueue_styles(): void {
		if ( ! $this->is_woocommerce_context() ) {
			return;
		}

		$this->enqueue_stylesheet();
	}

	/**
	 * Keep WooCommerce's classic frontend stylesheet bundle off non-commerce
	 * editorial pages. WooCommerce block/global styles still decide their own
	 * loading; this only removes the legacy catalog/cart CSS bundle.
	 *
	 * @param array<string,array<string,string>> $styles Registered WooCommerce styles.
	 * @return array<string,array<string,string>>
	 */
	public function filter_woocommerce_styles( array $styles ): array {
		if ( ! $this->is_woocommerce_context() ) {
			return [];
		}

		return $styles;
	}

	private function enqueue_stylesheet(): void {
		wp_enqueue_style(
			'hfb-woocommerce',
			HFB_URI . 'assets/css/woocommerce.css',
			[ 'hfb-theme' ],
			$this->asset_version( 'assets/css/woocommerce.css' )
		);
	}

	private function is_woocommerce_context(): bool {
		if ( ! class_exists( '\WooCommerce' ) ) {
			return false;
		}

		return ( function_exists( 'is_woocommerce' ) && is_woocommerce() )
			|| ( function_exists( 'is_cart' ) && is_cart() )
			|| ( function_exists( 'is_checkout' ) && is_checkout() )
			|| ( function_exists( 'is_account_page' ) && is_account_page() )
			|| $this->is_product_search()
			|| $this->has_woocommerce_blocks();
	}

	private function is_product_search(): bool {
		if ( ! is_search() ) {
			return false;
		}

		$post_type = get_query_var( 'post_type' );
		if ( is_array( $post_type ) ) {
			return in_array( 'product', $post_type, true );
		}

		return 'product' === $post_type;
	}

	private function has_woocommerce_blocks(): bool {
		if ( ! is_singular() ) {
			return false;
		}

		$post = get_post();
		if ( ! $post instanceof \WP_Post ) {
			return false;
		}

		foreach ( self::BLOCKS as $block_name ) {
			if ( has_block( $block_name, $post ) ) {
				return true;
			}
		}

		return false;
	}
}
