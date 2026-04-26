<?php
/**
 * Hungry Flamingo Blog — bootstrap.
 *
 * @package HFB
 */

declare( strict_types = 1 );

defined( 'ABSPATH' ) || exit;

/**
 * Theme constants.
 */
define( 'HFB_VERSION',   '1.0.0' );
define( 'HFB_DIR',       trailingslashit( get_template_directory() ) );
define( 'HFB_URI',       trailingslashit( get_template_directory_uri() ) );

if ( ! defined( 'HFB_DEFAULT_SCHEME' ) ) {
	define( 'HFB_DEFAULT_SCHEME', 'light' );
}

/**
 * PSR-4-ish autoloader for the HFB namespace.
 *
 * Maps HFB\Foo_Bar → inc/class-foo-bar.php.
 */
spl_autoload_register(
	/**
	 * Resolve an HFB-namespaced class name to its file path and require it.
	 *
	 * @param string $class Fully-qualified class name being autoloaded.
	 * @return void
	 */
	static function ( string $class ): void {
		if ( strpos( $class, 'HFB\\' ) !== 0 ) {
			return;
		}

		$relative = substr( $class, 4 );
		$parts    = explode( '\\', $relative );
		$file     = array_pop( $parts );
		$path     = array_map( static function ( string $segment ): string {
			return strtolower( str_replace( '_', '-', $segment ) );
		}, $parts );

		$file_name = 'class-' . strtolower( str_replace( '_', '-', $file ) ) . '.php';
		$full_path = HFB_DIR . 'inc/' . ( $path ? implode( '/', $path ) . '/' : '' ) . $file_name;

		if ( is_readable( $full_path ) ) {
			require_once $full_path;
		}
	}
);

/**
 * Boot the theme.
 */
add_action(
	'after_setup_theme',
	/**
	 * Instantiate and boot the HFB theme container on `after_setup_theme`.
	 *
	 * @return void
	 */
	static function (): void {
		( new HFB\Theme() )->boot();
	},
	0
);
