<?php
/**
 * Title: WooCommerce — Product collection
 * Slug: hungry-flamingo-blog/woocommerce-product-collection
 * Categories: hungry-flamingo-blog, query, woocommerce
 * Keywords: products, shop, collection, grid
 * Inserter: yes
 * Textdomain: hungry-flamingo-blog
 */
?>
<!-- wp:group {"align":"wide","className":"hfb-commerce-section","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide hfb-commerce-section">
	<!-- wp:group {"className":"section-header","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between","verticalAlignment":"bottom"}} -->
	<div class="wp-block-group section-header">
		<!-- wp:heading {"level":2,"className":"section-header__title"} -->
		<h2 class="section-header__title"><?php esc_html_e( 'Shop the edit', 'hungry-flamingo-blog' ); ?></h2>
		<!-- /wp:heading -->
		<!-- wp:paragraph {"className":"see-all"} -->
		<p class="see-all"><?php esc_html_e( 'Latest products', 'hungry-flamingo-blog' ); ?></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->

	<!-- wp:woocommerce/product-collection {"queryId":32,"query":{"perPage":6,"pages":0,"offset":0,"postType":"product","order":"desc","orderBy":"date","inherit":false},"displayLayout":{"type":"flex","columns":3,"shrinkColumns":true},"align":"wide","className":"hfb-product-collection"} -->
	<!-- wp:woocommerce/product-template -->
		<!-- wp:woocommerce/product-image {"showSaleBadge":false,"showProductLink":true,"imageSizing":"thumbnail","isDescendentOfQueryLoop":true} -->
			<!-- wp:woocommerce/product-sale-badge {"isDescendentOfQueryLoop":true,"align":"right"} /-->
		<!-- /wp:woocommerce/product-image -->
		<!-- wp:post-title {"textAlign":"center","level":3,"isLink":true,"fontSize":"md","__woocommerceNamespace":"woocommerce/product-collection/product-title"} /-->
		<!-- wp:woocommerce/product-price {"textAlign":"center","isDescendentOfQueryLoop":true} /-->
		<!-- wp:woocommerce/product-rating {"textAlign":"center","isDescendentOfQueryLoop":true} /-->
		<!-- wp:woocommerce/product-button {"textAlign":"center","isDescendentOfQueryLoop":true} /-->
	<!-- /wp:woocommerce/product-template -->
	<!-- /wp:woocommerce/product-collection -->
</div>
<!-- /wp:group -->
