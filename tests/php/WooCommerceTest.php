<?php
/**
 * WooCommerce compatibility tests.
 *
 * @package HFB
 */

declare( strict_types = 1 );

use HFB\WooCommerce;
use PHPUnit\Framework\TestCase;

final class WooCommerceTest extends TestCase {

	protected function tearDown(): void {
		unset( $GLOBALS['hfb_is_search'], $GLOBALS['hfb_query_vars'], $GLOBALS['hfb_is_singular'], $GLOBALS['hfb_current_post'], $GLOBALS['hfb_unregistered_patterns'] );
		parent::tearDown();
	}

	public function test_product_search_detects_scalar_post_type(): void {
		$GLOBALS['hfb_is_search']  = true;
		$GLOBALS['hfb_query_vars'] = [ 'post_type' => 'product' ];

		$this->assertTrue( $this->invokePrivateBool( new WooCommerce(), 'is_product_search' ) );
	}

	public function test_product_search_detects_array_post_type(): void {
		$GLOBALS['hfb_is_search']  = true;
		$GLOBALS['hfb_query_vars'] = [ 'post_type' => [ 'post', 'product' ] ];

		$this->assertTrue( $this->invokePrivateBool( new WooCommerce(), 'is_product_search' ) );
	}

	public function test_detects_woocommerce_blocks_on_singular_content(): void {
		$post = new WP_Post();
		$post->post_content = '<!-- wp:woocommerce/product-collection /-->';

		$GLOBALS['hfb_is_singular']  = true;
		$GLOBALS['hfb_current_post'] = $post;

		$this->assertTrue( $this->invokePrivateBool( new WooCommerce(), 'has_woocommerce_blocks' ) );
	}

	public function test_keeps_classic_woocommerce_styles_off_blog_pages(): void {
		$this->assertSame( [], ( new WooCommerce() )->filter_woocommerce_styles( [ 'woocommerce-general' => [] ] ) );
	}

	private function invokePrivateBool( WooCommerce $instance, string $method ): bool {
		$reflection = new ReflectionMethod( $instance, $method );
		return (bool) $reflection->invoke( $instance );
	}
}
