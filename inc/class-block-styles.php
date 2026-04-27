<?php
/**
 * First-party block style variations.
 *
 * @package HFB
 */

declare( strict_types = 1 );

namespace HFB;

defined( 'ABSPATH' ) || exit;

final class Block_Styles {

	public function register(): void {
		add_action( 'init', [ $this, 'register_block_styles' ] );
	}

	public function register_block_styles(): void {
		if ( ! function_exists( 'register_block_style' ) ) {
			return;
		}

		register_block_style(
			'core/quote',
			[
				'name'  => 'hfb-accent',
				'label' => __( 'Accent quote', 'hungry-flamingo-blog' ),
			]
		);

		register_block_style(
			'core/separator',
			[
				'name'  => 'hfb-flamingo',
				'label' => __( 'Flamingo dots', 'hungry-flamingo-blog' ),
			]
		);
	}
}
