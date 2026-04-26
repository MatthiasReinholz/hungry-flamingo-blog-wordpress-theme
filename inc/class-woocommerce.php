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

	public function register(): void {
		$this->register_theme_supports();

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

	public function enqueue_styles(): void {
		if ( ! $this->is_woocommerce_context() ) {
			return;
		}

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

		return has_block( 'woocommerce/product-collection', $post )
			|| has_block( 'woocommerce/product-template', $post )
			|| has_block( 'woocommerce/cart', $post )
			|| has_block( 'woocommerce/checkout', $post )
			|| has_block( 'woocommerce/mini-cart', $post )
			|| has_block( 'woocommerce/featured-product', $post )
			|| has_block( 'woocommerce/handpicked-products', $post );
	}

	private function asset_version( string $path ): string {
		$full  = HFB_DIR . ltrim( $path, '/' );
		$mtime = is_readable( $full ) ? @filemtime( $full ) : false;

		return false !== $mtime ? HFB_VERSION . '.' . $mtime : HFB_VERSION;
	}
}
