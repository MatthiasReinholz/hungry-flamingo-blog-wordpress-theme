<?php
/**
 * Front-end and editor asset loader.
 *
 * @package HFB
 */

declare( strict_types = 1 );

namespace HFB;

defined( 'ABSPATH' ) || exit;

final class Assets {

	use Asset_Version;

	public function register(): void {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_frontend' ] );
		add_action( 'wp_head', [ $this, 'print_color_scheme_boot' ], 1 );
		add_filter( 'render_block', [ $this, 'hydrate_dynamic_links' ] );
	}

	/**
	 * Enqueue stylesheet and module scripts.
	 */
	public function enqueue_frontend(): void {
		wp_enqueue_style(
			'hfb-theme',
			HFB_URI . 'assets/css/theme.css',
			[],
			$this->asset_version( 'assets/css/theme.css' )
		);

		wp_enqueue_script(
			'hfb-color-scheme',
			HFB_URI . 'assets/js/color-scheme.js',
			[],
			$this->asset_version( 'assets/js/color-scheme.js' ),
			true
		);

		wp_enqueue_script(
			'hfb-mobile-menu',
			HFB_URI . 'assets/js/mobile-menu.js',
			[],
			$this->asset_version( 'assets/js/mobile-menu.js' ),
			true
		);

		wp_enqueue_script(
			'hfb-site',
			HFB_URI . 'assets/js/site.js',
			[],
			$this->asset_version( 'assets/js/site.js' ),
			true
		);

		wp_localize_script(
			'hfb-site',
			'HFB_UI',
			[
				'homeUrl'     => esc_url_raw( home_url( '/' ) ),
				'articlesUrl' => esc_url_raw( $this->articles_url() ),
				'searchUrl'   => esc_url_raw( add_query_arg( 's', '', home_url( '/' ) ) ),
				'feedUrl'     => esc_url_raw( get_feed_link() ),
				'siteName'    => wp_strip_all_tags( get_bloginfo( 'name' ) ),
			]
		);
	}

	/**
	 * Replace marked fallback links with install-path-aware URLs for no-JS users.
	 *
	 * @param string $block_content Rendered block content.
	 */
	public function hydrate_dynamic_links( string $block_content ): string {
		if ( false === strpos( $block_content, 'data-hfb-' ) ) {
			return $block_content;
		}

		if ( ! class_exists( '\WP_HTML_Tag_Processor' ) ) {
			return $block_content;
		}

		$processor    = new \WP_HTML_Tag_Processor( $block_content );
		$articles_url = esc_url( $this->articles_url() );
		$search_url   = esc_url( add_query_arg( 's', '', home_url( '/' ) ) );
		$feed_url     = esc_url( get_feed_link() );
		$home_url     = esc_url( home_url( '/' ) );

		while ( $processor->next_tag() ) {
			if ( null !== $processor->get_attribute( 'data-hfb-home-link' ) ) {
				$processor->set_attribute( 'href', $articles_url );
			}

			if ( null !== $processor->get_attribute( 'data-hfb-search-link' ) ) {
				$processor->set_attribute( 'href', $search_url );
			}

			if ( null !== $processor->get_attribute( 'data-hfb-feed-link' ) ) {
				$processor->set_attribute( 'href', $feed_url );
			}

			if ( null !== $processor->get_attribute( 'data-hfb-search-form' ) ) {
				$processor->set_attribute( 'action', $home_url );
			}
		}

		return $processor->get_updated_html();
	}

	/**
	 * Inline boot script printed before any stylesheet so the theme paints in the
	 * correct color scheme on first frame (no flash).
	 */
	public function print_color_scheme_boot(): void {
		$scheme = Color_Scheme::resolve_from_cookie();
		$script = sprintf(
			'(function(){try{var c=document.cookie.match(/(?:^|; )hfb_theme=([^;]+)/);var t=c?decodeURIComponent(c[1]):null;if(t!=="dark"&&t!=="light"){t=window.matchMedia&&window.matchMedia("(prefers-color-scheme: dark)").matches?"dark":"light";}document.documentElement.setAttribute("data-theme",t);}catch(e){document.documentElement.setAttribute("data-theme",%s);}})();',
			wp_json_encode( $scheme )
		);

		wp_print_inline_script_tag(
			$script,
			[
				'id' => 'hfb-color-scheme-boot',
			]
		);
	}

	private function articles_url(): string {
		$posts_page = (int) get_option( 'page_for_posts' );
		if ( $posts_page > 0 ) {
			$permalink = get_permalink( $posts_page );
			if ( is_string( $permalink ) && '' !== $permalink ) {
				return $permalink;
			}
		}

		return home_url( '/' );
	}
}
