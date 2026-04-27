<?php
/**
 * Title: Latest article section
 * Slug: hungry-flamingo-blog/latest-section
 * Categories: hungry-flamingo-blog
 * Inserter: no
 * Textdomain: hungry-flamingo-blog
 *
 * @package HFB
 */
?>
<!-- wp:group {"className":"section section--articles","layout":{"type":"constrained"}} -->
<div id="latest-articles" class="wp-block-group section section--articles">
	<!-- wp:group {"className":"section-header","layout":{"type":"flex","justifyContent":"space-between"}} -->
	<div class="wp-block-group section-header">
		<!-- wp:heading {"level":2,"className":"section-header__title"} -->
		<h2 class="section-header__title"><?php esc_html_e( 'Latest in', 'hungry-flamingo-blog' ); ?> <em class="accent"><?php esc_html_e( 'craft', 'hungry-flamingo-blog' ); ?></em></h2>
		<!-- /wp:heading -->
		<!-- wp:html -->
		<a class="see-all" href="/" data-hfb-home-link><?php esc_html_e( 'All articles', 'hungry-flamingo-blog' ); ?></a>
		<!-- /wp:html -->
	</div>
	<!-- /wp:group -->

	<!-- wp:query {"queryId":1,"query":{"perPage":9,"pages":0,"offset":3,"postType":"post","order":"desc","orderBy":"date","sticky":"exclude"},"className":"article-grid article-grid--after-hero"} -->
	<div class="wp-block-query article-grid article-grid--after-hero">
		<!-- wp:post-template {"className":"article-grid__list"} -->
			<!-- wp:pattern {"slug":"hungry-flamingo-blog/article-card"} /-->
		<!-- /wp:post-template -->

		<!-- wp:query-no-results -->
			<!-- wp:paragraph -->
			<p><?php esc_html_e( 'No articles yet. Check back soon.', 'hungry-flamingo-blog' ); ?></p>
			<!-- /wp:paragraph -->
		<!-- /wp:query-no-results -->
	</div>
	<!-- /wp:query -->
</div>
<!-- /wp:group -->
