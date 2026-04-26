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
				'homeUrl'  => esc_url_raw( home_url( '/' ) ),
				'feedUrl'  => esc_url_raw( get_feed_link() ),
				'siteName' => wp_strip_all_tags( get_bloginfo( 'name' ) ),
			]
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
			'<script>(function(){try{var c=document.cookie.match(/(?:^|; )hfb_theme=([^;]+)/);var t=c?decodeURIComponent(c[1]):null;if(!t){t=window.matchMedia && window.matchMedia("(prefers-color-scheme: dark)").matches?"dark":"light";}document.documentElement.setAttribute("data-theme",t);}catch(e){document.documentElement.setAttribute("data-theme",%s);}})();</script>',
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
}
