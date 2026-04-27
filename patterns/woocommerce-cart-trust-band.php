<?php
/**
 * Title: WooCommerce — Cart trust band
 * Slug: hungry-flamingo-blog/woocommerce-cart-trust-band
 * Categories: hungry-flamingo-blog, woocommerce
 * Keywords: cart, checkout, trust, commerce
 * Inserter: yes
 * Textdomain: hungry-flamingo-blog
 */
if ( ! class_exists( '\WooCommerce' ) ) {
	return;
}
?>
<!-- wp:group {"align":"wide","className":"hfb-commerce-trust-band","layout":{"type":"grid","columnCount":3,"minimumColumnWidth":null}} -->
<div class="wp-block-group alignwide hfb-commerce-trust-band">
	<!-- wp:group {"className":"hfb-commerce-trust-band__item","layout":{"type":"constrained"}} -->
	<div class="wp-block-group hfb-commerce-trust-band__item">
		<!-- wp:heading {"level":3} -->
		<h3><?php esc_html_e( 'Clear checkout', 'hungry-flamingo-blog' ); ?></h3>
		<!-- /wp:heading -->
		<!-- wp:paragraph -->
		<p><?php esc_html_e( 'Keep payment, delivery, and return expectations concise before the final checkout step.', 'hungry-flamingo-blog' ); ?></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"className":"hfb-commerce-trust-band__item","layout":{"type":"constrained"}} -->
	<div class="wp-block-group hfb-commerce-trust-band__item">
		<!-- wp:heading {"level":3} -->
		<h3><?php esc_html_e( 'Support details', 'hungry-flamingo-blog' ); ?></h3>
		<!-- /wp:heading -->
		<!-- wp:paragraph -->
		<p><?php esc_html_e( 'Add the most useful support route for store questions, without adding extra form logic to the theme.', 'hungry-flamingo-blog' ); ?></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"className":"hfb-commerce-trust-band__item","layout":{"type":"constrained"}} -->
	<div class="wp-block-group hfb-commerce-trust-band__item">
		<!-- wp:heading {"level":3} -->
		<h3><?php esc_html_e( 'Plain-language policies', 'hungry-flamingo-blog' ); ?></h3>
		<!-- /wp:heading -->
		<!-- wp:paragraph -->
		<p><?php esc_html_e( 'Use this slot for shipping, returns, downloads, or fulfillment notes that reduce checkout uncertainty.', 'hungry-flamingo-blog' ); ?></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
