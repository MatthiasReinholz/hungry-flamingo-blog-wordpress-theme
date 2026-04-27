<?php
/**
 * Shared local asset version helper.
 *
 * @package HFB
 */

declare( strict_types = 1 );

namespace HFB;

defined( 'ABSPATH' ) || exit;

trait Asset_Version {

	/**
	 * Compute a cache-busting version string for a local theme asset.
	 *
	 * @param string $path Theme-relative path, e.g. `assets/css/theme.css`.
	 * @return string Version string suitable for `wp_enqueue_*` `$ver` argument.
	 */
	private function asset_version( string $path ): string {
		$full  = HFB_DIR . ltrim( $path, '/' );
		$mtime = is_readable( $full ) ? filemtime( $full ) : false;

		return false !== $mtime ? HFB_VERSION . '.' . $mtime : HFB_VERSION;
	}
}
