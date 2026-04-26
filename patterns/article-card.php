<?php
/**
 * Title: Article card
 * Slug: hungry-flamingo-blog/article-card
 * Categories: featured, hungry-flamingo-blog
 * Keywords: article, card, grid, post
 * Inserter: no
 * Textdomain: hungry-flamingo-blog
 */
?>
<!-- wp:group {"tagName":"article","className":"article-card","layout":{"type":"default"}} -->
<article class="wp-block-group article-card">
	<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"16/10","className":"article-card__thumb"} /-->

	<!-- wp:group {"className":"article-card__body"} -->
	<div class="wp-block-group article-card__body">
		<!-- wp:post-terms {"term":"category","className":"tag"} /-->
		<!-- wp:post-title {"level":3,"isLink":true,"className":"article-card__title"} /-->
		<!-- wp:post-excerpt {"className":"article-card__excerpt","moreText":"","showMoreOnNewLine":false,"excerptLength":22} /-->

		<!-- wp:group {"className":"card-meta","layout":{"type":"flex","flexWrap":"nowrap"}} -->
		<div class="wp-block-group card-meta">
			<!-- wp:avatar {"size":28,"className":"avatar avatar-sm"} /-->
			<!-- wp:post-author-name {"isLink":false} /-->
			<!-- wp:post-date {"isLink":false,"format":"M j"} /-->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</article>
<!-- /wp:group -->
