<?php
/**
 * Title: WooCommerce — Featured product editorial
 * Slug: hungry-flamingo-blog/woocommerce-featured-product
 * Categories: hungry-flamingo-blog, featured, woocommerce
 * Keywords: product, commerce, editorial, feature
 * Inserter: yes
 * Textdomain: hungry-flamingo-blog
 */
?>
<!-- wp:group {"align":"wide","className":"hfb-commerce-feature","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide hfb-commerce-feature">
	<!-- wp:columns {"verticalAlignment":"center","className":"hfb-commerce-feature__columns"} -->
	<div class="wp-block-columns are-vertically-aligned-center hfb-commerce-feature__columns">
		<!-- wp:column {"verticalAlignment":"center","width":"42%","className":"hfb-commerce-feature__copy"} -->
		<div class="wp-block-column is-vertically-aligned-center hfb-commerce-feature__copy" style="flex-basis:42%">
			<!-- wp:paragraph {"className":"tag"} -->
			<p class="tag"><?php esc_html_e( 'Editor pick', 'hungry-flamingo-blog' ); ?></p>
			<!-- /wp:paragraph -->
			<!-- wp:heading {"level":2} -->
			<h2><?php esc_html_e( 'A product worth a closer look', 'hungry-flamingo-blog' ); ?></h2>
			<!-- /wp:heading -->
			<!-- wp:paragraph -->
			<p><?php esc_html_e( 'Use this section when a product needs editorial context before readers move into the product card.', 'hungry-flamingo-blog' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"verticalAlignment":"center","width":"58%"} -->
		<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:58%">
			<!-- wp:woocommerce/product-collection {"queryId":31,"query":{"perPage":1,"pages":0,"offset":0,"postType":"product","order":"desc","orderBy":"date","inherit":false},"displayLayout":{"type":"flex","columns":1,"shrinkColumns":true},"className":"hfb-product-collection hfb-product-collection--featured"} -->
			<!-- wp:woocommerce/product-template -->
				<!-- wp:woocommerce/product-image {"showSaleBadge":false,"showProductLink":true,"imageSizing":"thumbnail","isDescendentOfQueryLoop":true} -->
					<!-- wp:woocommerce/product-sale-badge {"isDescendentOfQueryLoop":true,"align":"right"} /-->
				<!-- /wp:woocommerce/product-image -->
				<!-- wp:post-title {"textAlign":"center","level":3,"isLink":true,"fontSize":"lg","__woocommerceNamespace":"woocommerce/product-collection/product-title"} /-->
				<!-- wp:woocommerce/product-price {"textAlign":"center","isDescendentOfQueryLoop":true} /-->
				<!-- wp:woocommerce/product-rating {"textAlign":"center","isDescendentOfQueryLoop":true} /-->
				<!-- wp:woocommerce/product-button {"textAlign":"center","isDescendentOfQueryLoop":true} /-->
			<!-- /wp:woocommerce/product-template -->
			<!-- /wp:woocommerce/product-collection -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
