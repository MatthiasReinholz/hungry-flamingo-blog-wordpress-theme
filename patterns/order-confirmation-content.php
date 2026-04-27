<?php
/**
 * Title: WooCommerce order confirmation content
 * Slug: hungry-flamingo-blog/order-confirmation-content
 * Categories: hungry-flamingo-blog, woocommerce
 * Inserter: no
 * Textdomain: hungry-flamingo-blog
 *
 * @package HFB
 */
?>
<!-- wp:group {"className":"hfb-store-layout hfb-order-confirmation","layout":{"type":"constrained"}} -->
<div class="wp-block-group hfb-store-layout hfb-order-confirmation">
	<!-- wp:woocommerce/store-notices /-->
	<!-- wp:woocommerce/order-confirmation-status {"align":"wide"} /-->
	<!-- wp:woocommerce/order-confirmation-summary {"align":"wide"} /-->
	<!-- wp:woocommerce/order-confirmation-totals-wrapper {"heading":<?php echo wp_json_encode( __( 'Order details', 'hungry-flamingo-blog' ) ); ?>} -->
		<!-- wp:woocommerce/order-confirmation-totals {"align":"wide"} /-->
	<!-- /wp:woocommerce/order-confirmation-totals-wrapper -->
	<!-- wp:columns {"align":"wide"} -->
	<div class="wp-block-columns alignwide">
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:woocommerce/order-confirmation-billing-wrapper {"heading":<?php echo wp_json_encode( __( 'Billing address', 'hungry-flamingo-blog' ) ); ?>} -->
				<!-- wp:woocommerce/order-confirmation-billing-address {"align":"wide"} /-->
			<!-- /wp:woocommerce/order-confirmation-billing-wrapper -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:woocommerce/order-confirmation-shipping-wrapper {"heading":<?php echo wp_json_encode( __( 'Shipping address', 'hungry-flamingo-blog' ) ); ?>} -->
				<!-- wp:woocommerce/order-confirmation-shipping-address {"align":"wide"} /-->
			<!-- /wp:woocommerce/order-confirmation-shipping-wrapper -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
	<!-- wp:woocommerce/order-confirmation-downloads-wrapper {"heading":<?php echo wp_json_encode( __( 'Downloads', 'hungry-flamingo-blog' ) ); ?>} -->
		<!-- wp:woocommerce/order-confirmation-downloads {"align":"wide"} /-->
	<!-- /wp:woocommerce/order-confirmation-downloads-wrapper -->
	<!-- wp:woocommerce/order-confirmation-additional-information {"align":"wide"} /-->
</div>
<!-- /wp:group -->
