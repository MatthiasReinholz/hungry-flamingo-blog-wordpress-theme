<?php
/**
 * Main theme bootstrap.
 *
 * Kept intentionally thin: its only job is to register theme supports and the
 * set of feature modules.
 *
 * @package HFB
 */

declare( strict_types = 1 );

namespace HFB;

defined( 'ABSPATH' ) || exit;

final class Theme {

	/**
	 * Feature modules. Each must expose a register(): void method.
	 *
	 * @var string[]
	 */
	private $modules = [
		Assets::class,
		Color_Scheme::class,
		WooCommerce::class,
	];

	public function boot(): void {
		$this->register_theme_supports();
		$this->register_modules();

		add_action( 'init', [ $this, 'register_pattern_category' ] );
		add_filter( 'query_loop_block_query_vars', [ $this, 'exclude_current_post_from_related_query' ], 10, 2 );
	}

	public function register_pattern_category(): void {
		if ( function_exists( 'register_block_pattern_category' ) ) {
			register_block_pattern_category(
				'hungry-flamingo-blog',
				[ 'label' => __( 'Hungry Flamingo', 'hungry-flamingo-blog' ) ]
			);
		}
	}

	/**
	 * Prevent the single-post sidebar's related query from linking to itself.
	 *
	 * @param array<string,mixed>       $query Query vars prepared for the Query Loop block.
	 * @param \WP_Block|array<string,mixed> $block Query Loop block instance or parsed block data.
	 * @return array<string,mixed>
	 */
	public function exclude_current_post_from_related_query( array $query, $block ): array {
		if ( ! is_singular( 'post' ) ) {
			return $query;
		}

		$attrs = [];
		if ( $block instanceof \WP_Block ) {
			$attrs = isset( $block->parsed_block['attrs'] ) && is_array( $block->parsed_block['attrs'] )
				? $block->parsed_block['attrs']
				: [];
		} elseif ( is_array( $block ) ) {
			$attrs = isset( $block['attrs'] ) && is_array( $block['attrs'] ) ? $block['attrs'] : [];
		}

		$class_name = isset( $attrs['className'] ) && is_string( $attrs['className'] ) ? $attrs['className'] : '';

		if ( ! preg_match( '/(^|\s)related-query(\s|$)/', $class_name ) ) {
			return $query;
		}

		$post_id = get_queried_object_id();
		if ( ! $post_id ) {
			return $query;
		}

		$excluded = isset( $query['post__not_in'] ) ? (array) $query['post__not_in'] : [];
		$excluded = array_filter( array_map( 'absint', $excluded ) );
		$excluded[] = (int) $post_id;
		$query['post__not_in'] = array_values( array_unique( $excluded ) );

		return $query;
	}

	private function register_theme_supports(): void {
		load_theme_textdomain( 'hungry-flamingo-blog', HFB_DIR . 'languages' );

		add_theme_support( 'wp-block-styles' );
		add_theme_support( 'editor-styles' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'align-wide' );
		add_theme_support( 'custom-logo', [
			'width'       => 120,
			'height'      => 120,
			'flex-width'  => true,
			'flex-height' => true,
		] );
		add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ] );
		add_theme_support( 'post-formats', [ 'aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat' ] );

		add_editor_style( [ 'assets/css/theme.css', 'assets/css/woocommerce.css', 'assets/css/editor.css' ] );
	}

	private function register_modules(): void {
		foreach ( $this->modules as $module_class ) {
			if ( class_exists( $module_class ) ) {
				$instance = new $module_class();
				if ( method_exists( $instance, 'register' ) ) {
					$instance->register();
				}
			}
		}
	}
}
