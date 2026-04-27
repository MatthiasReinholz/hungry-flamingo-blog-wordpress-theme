<?php
/**
 * Title: WooCommerce — Article product callout
 * Slug: hungry-flamingo-blog/woocommerce-post-product-cta
 * Categories: hungry-flamingo-blog, call-to-action, woocommerce
 * Keywords: article, product, callout, commerce
 * Inserter: yes
 * Textdomain: hungry-flamingo-blog
 */
if ( ! class_exists( '\WooCommerce' ) ) {
	return;
}

$hfb_shop_url = home_url( '/' );
if ( function_exists( 'wc_get_page_permalink' ) ) {
	$hfb_woocommerce_shop_url = wc_get_page_permalink( 'shop' );
	if ( is_string( $hfb_woocommerce_shop_url ) && '' !== $hfb_woocommerce_shop_url ) {
		$hfb_shop_url = $hfb_woocommerce_shop_url;
	}
}
?>
<!-- wp:group {"className":"hfb-commerce-callout","layout":{"type":"constrained"}} -->
<div class="wp-block-group hfb-commerce-callout">
	<!-- wp:paragraph {"className":"tag"} -->
	<p class="tag"><?php esc_html_e( 'From the shop', 'hungry-flamingo-blog' ); ?></p>
	<!-- /wp:paragraph -->
	<!-- wp:heading {"level":3} -->
	<h3><?php esc_html_e( 'Pair this article with a practical next step', 'hungry-flamingo-blog' ); ?></h3>
	<!-- /wp:heading -->
	<!-- wp:paragraph -->
	<p><?php esc_html_e( 'Replace this text with a short, honest product note that helps readers decide whether the product fits the article.', 'hungry-flamingo-blog' ); ?></p>
	<!-- /wp:paragraph -->
	<!-- wp:buttons -->
	<div class="wp-block-buttons">
		<!-- wp:button -->
		<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $hfb_shop_url ); ?>"><?php esc_html_e( 'View shop', 'hungry-flamingo-blog' ); ?></a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
