<?php
/**
 * Title: 404 content
 * Slug: hungry-flamingo-blog/error-404
 * Categories: hungry-flamingo-blog
 * Inserter: no
 * Textdomain: hungry-flamingo-blog
 *
 * @package HFB
 */
?>
<!-- wp:group {"className":"error-404","layout":{"type":"constrained"}} -->
<div class="wp-block-group error-404">
	<!-- wp:heading {"level":1,"className":"error-404__title"} -->
	<h1 class="error-404__title"><?php esc_html_e( '404 — this page flew away.', 'hungry-flamingo-blog' ); ?></h1>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"className":"error-404__lede"} -->
	<p class="error-404__lede"><?php esc_html_e( 'The link might be broken, or the piece has been moved. Try searching, or head home.', 'hungry-flamingo-blog' ); ?></p>
	<!-- /wp:paragraph -->

	<!-- wp:search {"label":<?php echo wp_json_encode( __( 'Search', 'hungry-flamingo-blog' ) ); ?>,"buttonText":<?php echo wp_json_encode( __( 'Search', 'hungry-flamingo-blog' ) ); ?>,"buttonPosition":"button-inside","className":"hfb-search"} /-->

	<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
	<div class="wp-block-buttons">
		<!-- wp:button -->
		<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="/" data-hfb-home-link><?php esc_html_e( 'Go home', 'hungry-flamingo-blog' ); ?></a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
