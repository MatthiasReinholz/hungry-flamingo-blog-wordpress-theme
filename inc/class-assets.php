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

		return str_replace(
			[
				'href="/" data-hfb-home-link',
				'href="/?s=" data-hfb-search-link',
				'href="/feed/" data-hfb-feed-link',
				'action="/" data-hfb-search-form',
			],
			[
				'href="' . esc_url( $this->articles_url() ) . '" data-hfb-home-link',
				'href="' . esc_url( add_query_arg( 's', '', home_url( '/' ) ) ) . '" data-hfb-search-link',
				'href="' . esc_url( get_feed_link() ) . '" data-hfb-feed-link',
				'action="' . esc_url( home_url( '/' ) ) . '" data-hfb-search-form',
			],
			$block_content
		);
	}

	/**
	 * Inline boot script printed before any stylesheet so the theme paints in the
	 * correct color scheme on first frame (no flash).
	 */
	public function print_color_scheme_boot(): void {
		$default = defined( 'HFB_DEFAULT_SCHEME' ) ? HFB_DEFAULT_SCHEME : 'light';
		$cookie  = isset( $_COOKIE['hfb_theme'] ) ? sanitize_key( wp_unslash( $_COOKIE['hfb_theme'] ) ) : '';
		$scheme  = in_array( $cookie, [ 'light', 'dark' ], true ) ? $cookie : $default;

		printf(
			'<script>(function(){try{var c=document.cookie.match(/(?:^|; )hfb_theme=([^;]+)/);var t=c?decodeURIComponent(c[1]):null;if(t!=="dark"&&t!=="light"){t=window.matchMedia&&window.matchMedia("(prefers-color-scheme: dark)").matches?"dark":"light";}document.documentElement.setAttribute("data-theme",t);}catch(e){document.documentElement.setAttribute("data-theme",%s);}})();</script>',
			wp_json_encode( $scheme )
		);
	}

	/**
	 * Compute a cache-busting version string for a local theme asset.
	 *
	 * Returns `HFB_VERSION.<mtime>` when the file exists, otherwise falls back
	 * to `HFB_VERSION`. Suppresses warnings so a missing file never emits noise.
	 *
	 * @param string $path Theme-relative path, e.g. `assets/css/theme.css`.
	 * @return string Version string suitable for `wp_enqueue_*` `$ver` argument.
	 */
	private function asset_version( string $path ): string {
		$full = HFB_DIR . ltrim( $path, '/' );
		$mtime = is_readable( $full ) ? @filemtime( $full ) : false;
		return false !== $mtime ? HFB_VERSION . '.' . $mtime : HFB_VERSION;
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
