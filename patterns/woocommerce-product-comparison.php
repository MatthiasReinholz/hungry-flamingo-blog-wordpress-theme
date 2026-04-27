<?php
/**
 * Title: WooCommerce — Product comparison
 * Slug: hungry-flamingo-blog/woocommerce-product-comparison
 * Categories: hungry-flamingo-blog, text, woocommerce
 * Keywords: product, comparison, table, commerce
 * Inserter: yes
 * Textdomain: hungry-flamingo-blog
 */
if ( ! class_exists( '\WooCommerce' ) ) {
	return;
}
?>
<!-- wp:group {"align":"wide","className":"hfb-commerce-section hfb-product-comparison","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide hfb-commerce-section hfb-product-comparison">
	<!-- wp:heading {"level":2} -->
	<h2><?php esc_html_e( 'Compare before you choose', 'hungry-flamingo-blog' ); ?></h2>
	<!-- /wp:heading -->

	<!-- wp:table {"className":"hfb-comparison-table"} -->
	<figure class="wp-block-table hfb-comparison-table"><table><thead><tr><th><?php esc_html_e( 'Product', 'hungry-flamingo-blog' ); ?></th><th><?php esc_html_e( 'Best for', 'hungry-flamingo-blog' ); ?></th><th><?php esc_html_e( 'Key detail', 'hungry-flamingo-blog' ); ?></th><th><?php esc_html_e( 'Price', 'hungry-flamingo-blog' ); ?></th></tr></thead><tbody><tr><td><?php esc_html_e( 'Product one', 'hungry-flamingo-blog' ); ?></td><td><?php esc_html_e( 'Everyday use', 'hungry-flamingo-blog' ); ?></td><td><?php esc_html_e( 'Balanced feature set', 'hungry-flamingo-blog' ); ?></td><td><?php esc_html_e( 'Add current price', 'hungry-flamingo-blog' ); ?></td></tr><tr><td><?php esc_html_e( 'Product two', 'hungry-flamingo-blog' ); ?></td><td><?php esc_html_e( 'Power users', 'hungry-flamingo-blog' ); ?></td><td><?php esc_html_e( 'Advanced configuration', 'hungry-flamingo-blog' ); ?></td><td><?php esc_html_e( 'Add current price', 'hungry-flamingo-blog' ); ?></td></tr><tr><td><?php esc_html_e( 'Product three', 'hungry-flamingo-blog' ); ?></td><td><?php esc_html_e( 'Budget pick', 'hungry-flamingo-blog' ); ?></td><td><?php esc_html_e( 'Simple and direct', 'hungry-flamingo-blog' ); ?></td><td><?php esc_html_e( 'Add current price', 'hungry-flamingo-blog' ); ?></td></tr></tbody></table></figure>
	<!-- /wp:table -->
</div>
<!-- /wp:group -->
