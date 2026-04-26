<?php
/**
 * Title: Home hero — featured + three secondaries
 * Slug: hungry-flamingo-blog/hero
 * Categories: featured, hungry-flamingo-blog
 * Keywords: hero, featured, home
 * Block Types: core/template-part/hero
 * Textdomain: hungry-flamingo-blog
 */
?>
<!-- wp:group {"className":"hero","layout":{"type":"constrained"}} -->
<div class="wp-block-group hero">
	<div class="hero__glow hero__glow--flamingo" aria-hidden="true"></div>
	<div class="hero__glow hero__glow--teal" aria-hidden="true"></div>

	<!-- wp:group {"className":"hero-inner","layout":{"type":"default"}} -->
	<div class="wp-block-group hero-inner">

		<!-- wp:query {"queryId":10,"query":{"perPage":1,"postType":"post","order":"desc","orderBy":"date","sticky":"only"},"className":"hero-lead"} -->
		<div class="wp-block-query hero-lead">
			<!-- wp:post-template -->
				<!-- wp:group {"className":"hero-lead__inner"} -->
				<div class="wp-block-group hero-lead__inner">
					<!-- wp:html -->
					<span class="hero-eyebrow"><span class="dot">F</span><span><?php printf(
						/* translators: %s: publication date */
						esc_html__( 'Issue — %s', 'hungry-flamingo-blog' ),
						esc_html( wp_date( get_option( 'date_format' ) ) )
					); ?></span></span>
					<!-- /wp:html -->
					<!-- wp:post-title {"level":1,"isLink":true,"className":"hero-headline"} /-->
					<!-- wp:post-excerpt {"className":"hero-lede","showMoreOnNewLine":false} /-->
					<!-- wp:group {"className":"hero-meta","layout":{"type":"flex"}} -->
					<div class="wp-block-group hero-meta">
						<!-- wp:avatar {"size":40,"className":"avatar"} /-->
						<!-- wp:group {"className":"hero-meta__copy"} -->
						<div class="wp-block-group hero-meta__copy">
							<!-- wp:post-author-name {"isLink":false} /-->
							<!-- wp:post-date {"isLink":false,"format":"M j, Y"} /-->
						</div>
						<!-- /wp:group -->
					</div>
					<!-- /wp:group -->
				</div>
				<!-- /wp:group -->
			<!-- /wp:post-template -->

			<!-- wp:query-no-results -->
				<!-- wp:group {"className":"hero-lead__inner"} -->
				<div class="wp-block-group hero-lead__inner">
					<!-- wp:heading {"level":1,"className":"hero-headline"} -->
					<h1 class="hero-headline">Writing for <em>readers</em> in the age of infinite scroll.</h1>
					<!-- /wp:heading -->
					<!-- wp:paragraph {"className":"hero-lede"} -->
					<p class="hero-lede">A field manual for creators who still believe the long sentence can win.</p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->
			<!-- /wp:query-no-results -->
		</div>
		<!-- /wp:query -->

		<!-- wp:query {"queryId":11,"query":{"perPage":3,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","sticky":"exclude"},"className":"hero-secondary"} -->
		<div class="wp-block-query hero-secondary">
			<!-- wp:post-template -->
				<!-- wp:group {"tagName":"article","className":"hero-secondary-card","layout":{"type":"flex","flexWrap":"nowrap"}} -->
				<article class="wp-block-group hero-secondary-card">
					<!-- wp:group {"className":"hero-secondary-card__body"} -->
					<div class="wp-block-group hero-secondary-card__body">
						<!-- wp:post-terms {"term":"category","className":"tag"} /-->
						<!-- wp:post-title {"level":3,"isLink":true} /-->
						<!-- wp:post-author-name {"isLink":false,"className":"meta"} /-->
					</div>
					<!-- /wp:group -->
					<!-- wp:post-featured-image {"isLink":true,"width":"120px","aspectRatio":"4/3","className":"thumb"} /-->
				</article>
				<!-- /wp:group -->
			<!-- /wp:post-template -->
		</div>
		<!-- /wp:query -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
