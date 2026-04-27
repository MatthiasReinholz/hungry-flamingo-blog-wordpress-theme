<?php
/**
 * Title: WooCommerce — Post-purchase reading
 * Slug: hungry-flamingo-blog/woocommerce-post-purchase-reading
 * Categories: hungry-flamingo-blog, query, woocommerce
 * Keywords: order, confirmation, reading, articles
 * Inserter: yes
 * Textdomain: hungry-flamingo-blog
 */
if ( ! class_exists( '\WooCommerce' ) ) {
	return;
}
?>
<!-- wp:group {"align":"wide","className":"hfb-commerce-section hfb-post-purchase-reading","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide hfb-commerce-section hfb-post-purchase-reading">
	<!-- wp:heading {"level":2} -->
	<h2><?php esc_html_e( 'While you wait', 'hungry-flamingo-blog' ); ?></h2>
	<!-- /wp:heading -->
	<!-- wp:paragraph -->
	<p><?php esc_html_e( 'Recommend recent articles after purchase without changing WooCommerce order behavior.', 'hungry-flamingo-blog' ); ?></p>
	<!-- /wp:paragraph -->
	<!-- wp:query {"queryId":33,"query":{"perPage":3,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","inherit":false},"className":"article-grid"} -->
	<div class="wp-block-query article-grid">
		<!-- wp:post-template {"className":"article-grid__list"} -->
			<!-- wp:pattern {"slug":"hungry-flamingo-blog/article-card"} /-->
		<!-- /wp:post-template -->
	</div>
	<!-- /wp:query -->
</div>
<!-- /wp:group -->
