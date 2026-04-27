<?php
/**
 * Title: Article sidebar
 * Slug: hungry-flamingo-blog/site-sidebar
 * Categories: hungry-flamingo-blog
 * Inserter: no
 * Textdomain: hungry-flamingo-blog
 *
 * @package HFB
 */
?>
<!-- wp:group {"tagName":"aside","className":"hfb-sidebar","layout":{"type":"constrained"}} -->
<aside class="wp-block-group hfb-sidebar" aria-label="<?php esc_attr_e( 'Article sidebar', 'hungry-flamingo-blog' ); ?>">
	<!-- wp:group {"className":"widget hfb-widget-toc"} -->
	<div class="wp-block-group widget hfb-widget-toc">
		<!-- wp:heading {"level":4,"className":"widget-title"} -->
		<h4 class="widget-title"><?php esc_html_e( 'On this page', 'hungry-flamingo-blog' ); ?></h4>
		<!-- /wp:heading -->
		<!-- wp:html -->
		<ul class="toc" data-hfb-toc aria-label="<?php esc_attr_e( 'Table of contents', 'hungry-flamingo-blog' ); ?>"></ul>
		<!-- /wp:html -->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"className":"widget author-widget"} -->
	<div class="wp-block-group widget author-widget">
		<!-- wp:html -->
		<div data-hfb-author-card></div>
		<!-- /wp:html -->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"className":"widget feed-widget"} -->
	<div class="wp-block-group widget feed-widget">
		<!-- wp:html -->
		<h4 class="widget-title"><?php esc_html_e( 'RSS updates', 'hungry-flamingo-blog' ); ?></h4>
		<h4><?php esc_html_e( 'Follow new essays.', 'hungry-flamingo-blog' ); ?></h4>
		<p><?php esc_html_e( 'Use the feed in your reader for every new essay.', 'hungry-flamingo-blog' ); ?></p>
		<a class="feed-widget__link" href="/feed/" data-hfb-feed-link><?php esc_html_e( 'Open RSS feed', 'hungry-flamingo-blog' ); ?></a>
		<!-- /wp:html -->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"className":"widget hfb-widget-latest"} -->
	<div class="wp-block-group widget hfb-widget-latest">
		<!-- wp:heading {"level":4,"className":"widget-title"} -->
		<h4 class="widget-title"><?php esc_html_e( 'Latest articles', 'hungry-flamingo-blog' ); ?></h4>
		<!-- /wp:heading -->
		<!-- wp:query {"queryId":99,"query":{"perPage":4,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","exclude":[],"sticky":""},"className":"latest-query"} -->
		<div class="wp-block-query latest-query">
			<!-- wp:post-template {"className":"latest-list"} -->
			<!-- wp:group {"tagName":"article","className":"latest-item","layout":{"type":"flex","flexWrap":"nowrap"}} -->
			<article class="wp-block-group latest-item">
				<!-- wp:post-featured-image {"isLink":true,"width":"60px","height":"60px","aspectRatio":"1","className":"latest-thumb"} /-->
				<!-- wp:group {"className":"latest-meta"} -->
				<div class="wp-block-group latest-meta">
					<!-- wp:post-title {"level":5,"isLink":true,"fontSize":"sm"} /-->
					<!-- wp:post-author-name {"isLink":false} /-->
				</div>
				<!-- /wp:group -->
			</article>
			<!-- /wp:group -->
			<!-- /wp:post-template -->
		</div>
		<!-- /wp:query -->
	</div>
	<!-- /wp:group -->
</aside>
<!-- /wp:group -->
