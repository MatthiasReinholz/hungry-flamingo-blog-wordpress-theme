<?php
/**
 * Title: Site footer
 * Slug: hungry-flamingo-blog/site-footer
 * Categories: hungry-flamingo-blog
 * Inserter: no
 * Textdomain: hungry-flamingo-blog
 *
 * @package HFB
 */

$hfb_site_name = wp_strip_all_tags( get_bloginfo( 'name' ) );
?>
<!-- wp:group {"tagName":"footer","className":"site-footer","align":"full","layout":{"type":"constrained"}} -->
<footer class="wp-block-group alignfull site-footer">
	<!-- wp:group {"className":"footer-inner","layout":{"type":"constrained"}} -->
	<div class="wp-block-group footer-inner">
		<!-- wp:group {"className":"footer-top","layout":{"type":"grid","columnCount":4}} -->
		<div class="wp-block-group footer-top">
			<!-- wp:group {"className":"footer-brand-col"} -->
			<div class="wp-block-group footer-brand-col">
				<!-- wp:html -->
				<div class="footer-brand">
					<span class="brand-mark">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 3c0 2-1.5 3.5-3.5 3.5S7 5 7 3"/><path d="M12 6.5V13"/><path d="M12 13c-3.5 0-6 2.5-6 6h12c0-3.5-2.5-6-6-6z"/></svg>
					</span>
					<span data-hfb-site-name><?php echo esc_html( $hfb_site_name ); ?></span>
				</div>
				<p class="footer-tagline"><?php esc_html_e( 'A blog for writers, readers, and the people who make a living in the quiet space between them.', 'hungry-flamingo-blog' ); ?></p>

				<div class="footer-feed">
					<h4><?php esc_html_e( 'Follow along', 'hungry-flamingo-blog' ); ?></h4>
					<a class="footer-feed-link" href="/feed/" data-hfb-feed-link><?php esc_html_e( 'RSS feed', 'hungry-flamingo-blog' ); ?></a>
				</div>
				<!-- /wp:html -->
			</div>
			<!-- /wp:group -->

			<!-- wp:group {"className":"footer-col"} -->
			<div class="wp-block-group footer-col">
				<!-- wp:heading {"level":5} --><h5><?php esc_html_e( 'Explore', 'hungry-flamingo-blog' ); ?></h5><!-- /wp:heading -->
				<!-- wp:html -->
				<nav aria-label="<?php esc_attr_e( 'Footer navigation', 'hungry-flamingo-blog' ); ?>">
					<ul class="footer-menu">
						<li><a href="/" data-hfb-home-link><?php esc_html_e( 'Articles', 'hungry-flamingo-blog' ); ?></a></li>
						<li><a href="/?s=" data-hfb-search-link><?php esc_html_e( 'Search', 'hungry-flamingo-blog' ); ?></a></li>
						<li><a href="/feed/" data-hfb-feed-link><?php esc_html_e( 'RSS', 'hungry-flamingo-blog' ); ?></a></li>
					</ul>
				</nav>
				<!-- /wp:html -->
			</div>
			<!-- /wp:group -->

			<!-- wp:group {"className":"footer-col"} -->
			<div class="wp-block-group footer-col">
				<!-- wp:heading {"level":5} --><h5><?php esc_html_e( 'Resources', 'hungry-flamingo-blog' ); ?></h5><!-- /wp:heading -->
				<!-- wp:categories {"showHierarchy":false,"showPostCounts":false,"className":"footer-menu"} /-->
			</div>
			<!-- /wp:group -->

			<!-- wp:group {"className":"footer-col"} -->
			<div class="wp-block-group footer-col">
				<!-- wp:heading {"level":5} --><h5><?php esc_html_e( 'Community', 'hungry-flamingo-blog' ); ?></h5><!-- /wp:heading -->
				<!-- wp:archives {"showPostCounts":false,"type":"monthly","className":"footer-menu"} /-->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->

		<!-- wp:html -->
		<div class="footer-bottom">
			<div>&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> <span data-hfb-site-name><?php echo esc_html( $hfb_site_name ); ?></span> &middot; <?php esc_html_e( 'Built with care on WordPress', 'hungry-flamingo-blog' ); ?></div>
			<div class="footer-socials">
				<a href="/feed/" aria-label="<?php esc_attr_e( 'RSS feed', 'hungry-flamingo-blog' ); ?>" data-hfb-feed-link><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M4 11a9 9 0 0 1 9 9"/><path d="M4 4a16 16 0 0 1 16 16"/><circle cx="5" cy="19" r="1.5" fill="currentColor"/></svg></a>
			</div>
		</div>
		<!-- /wp:html -->
	</div>
	<!-- /wp:group -->
</footer>
<!-- /wp:group -->
