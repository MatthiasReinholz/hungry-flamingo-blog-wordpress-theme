<?php
/**
 * Title: Site header
 * Slug: hungry-flamingo-blog/site-header
 * Categories: hungry-flamingo-blog
 * Inserter: no
 * Textdomain: hungry-flamingo-blog
 *
 * @package HFB
 */
?>
<!-- wp:group {"tagName":"header","className":"site-header","align":"full","layout":{"type":"constrained"}} -->
<header class="wp-block-group alignfull site-header">
	<!-- wp:html -->
	<a class="skip-link" href="#site-content"><?php esc_html_e( 'Skip to content', 'hungry-flamingo-blog' ); ?></a>
	<!-- /wp:html -->

	<!-- wp:group {"className":"header-inner","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
	<div class="wp-block-group header-inner">
		<!-- wp:group {"className":"brand","layout":{"type":"flex","flexWrap":"nowrap"}} -->
		<div class="wp-block-group brand">
			<!-- wp:site-logo {"width":36,"shouldSyncIcon":false,"className":"brand-logo"} /-->
			<!-- wp:html -->
			<span class="brand__link" aria-hidden="true">
				<span class="brand-mark">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<path d="M14 3c0 2-1.5 3.5-3.5 3.5S7 5 7 3"/>
						<path d="M12 6.5V13"/>
						<path d="M12 13c-3.5 0-6 2.5-6 6h12c0-3.5-2.5-6-6-6z"/>
						<circle cx="12" cy="4.5" r="1" fill="currentColor"/>
					</svg>
				</span>
			</span>
			<!-- /wp:html -->
			<!-- wp:site-title {"level":0,"className":"brand-text"} /-->
		</div>
		<!-- /wp:group -->

		<!-- wp:html -->
		<nav class="primary-nav" aria-label="<?php esc_attr_e( 'Primary navigation', 'hungry-flamingo-blog' ); ?>">
			<a href="/" data-hfb-home-link><?php esc_html_e( 'Articles', 'hungry-flamingo-blog' ); ?></a>
			<a href="/?s=" data-hfb-search-link><?php esc_html_e( 'Search', 'hungry-flamingo-blog' ); ?></a>
			<a href="/feed/" data-hfb-feed-link><?php esc_html_e( 'RSS', 'hungry-flamingo-blog' ); ?></a>
		</nav>
		<!-- /wp:html -->

		<!-- wp:group {"className":"header-actions","layout":{"type":"flex","flexWrap":"nowrap"}} -->
		<div class="wp-block-group header-actions">
			<!-- wp:html -->
			<button type="button" class="icon-btn" aria-label="<?php esc_attr_e( 'Search', 'hungry-flamingo-blog' ); ?>" data-hfb-search>
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
			</button>
			<button type="button" class="icon-btn" id="hfb-theme-toggle" aria-label="<?php esc_attr_e( 'Toggle theme', 'hungry-flamingo-blog' ); ?>" aria-pressed="false">
				<svg class="icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/></svg>
				<svg class="icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
			</button>
			<a href="/feed/" class="feed-btn" data-hfb-feed-link>
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><path d="M4 6h16v12H4z"/><path d="m4 7 8 6 8-6"/></svg>
				<?php esc_html_e( 'RSS', 'hungry-flamingo-blog' ); ?>
			</a>
			<button type="button" class="icon-btn burger" id="hfb-burger" aria-label="<?php esc_attr_e( 'Open menu', 'hungry-flamingo-blog' ); ?>" aria-expanded="false" aria-controls="hfb-mobile-menu">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
			</button>
			<!-- /wp:html -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->

	<!-- wp:html -->
	<div id="hfb-mobile-menu" class="mobile-menu" role="dialog" aria-modal="true" aria-labelledby="hfb-mobile-menu-title" hidden>
		<div class="mobile-menu-top">
			<span class="brand" id="hfb-mobile-menu-title">
				<span class="brand-mark">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 3c0 2-1.5 3.5-3.5 3.5S7 5 7 3"/><path d="M12 6.5V13"/><path d="M12 13c-3.5 0-6 2.5-6 6h12c0-3.5-2.5-6-6-6z"/></svg>
				</span>
				<strong data-hfb-site-name><?php echo esc_html( wp_strip_all_tags( get_bloginfo( 'name' ) ) ); ?></strong>
			</span>
			<button type="button" class="icon-btn" id="hfb-close-menu" aria-label="<?php esc_attr_e( 'Close menu', 'hungry-flamingo-blog' ); ?>">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><path d="m18 6-12 12M6 6l12 12"/></svg>
			</button>
		</div>
		<nav aria-label="<?php esc_attr_e( 'Mobile navigation', 'hungry-flamingo-blog' ); ?>" data-hfb-mobile-nav></nav>
		<div class="mobile-menu-footer"><span>&copy; <span data-hfb-site-name><?php echo esc_html( wp_strip_all_tags( get_bloginfo( 'name' ) ) ); ?></span></span></div>
	</div>

	<div id="hfb-search-dialog" class="search-dialog" role="dialog" aria-modal="true" aria-labelledby="hfb-search-title" hidden>
		<div class="search-dialog__panel">
			<div class="search-dialog__top">
				<h2 id="hfb-search-title"><?php esc_html_e( 'Search', 'hungry-flamingo-blog' ); ?></h2>
				<button type="button" class="icon-btn" aria-label="<?php esc_attr_e( 'Close search', 'hungry-flamingo-blog' ); ?>" data-hfb-search-close>
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><path d="m18 6-12 12M6 6l12 12"/></svg>
				</button>
			</div>
			<form class="search-dialog__form" role="search" method="get" action="/" data-hfb-search-form>
				<label for="hfb-search-field" class="hfb-sr-only"><?php esc_html_e( 'Search terms', 'hungry-flamingo-blog' ); ?></label>
				<input id="hfb-search-field" type="search" name="s" placeholder="<?php esc_attr_e( 'Search articles', 'hungry-flamingo-blog' ); ?>" autocomplete="off" data-hfb-search-input />
				<button type="submit"><?php esc_html_e( 'Search', 'hungry-flamingo-blog' ); ?></button>
			</form>
		</div>
	</div>
	<!-- /wp:html -->
</header>
<!-- /wp:group -->
