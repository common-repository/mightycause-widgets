<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class for registering and rendering Gutenberg blocks.
 */
class Mightycause_Blocks {

	/**
	 * Constructor method.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_blocks' ) );
		add_action( 'rest_api_init', array( $this, 'register_rest_routes' ) );
	}

	/**
	 * Register Gutenberg blocks.
	 */
	public function register_blocks() {
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		register_block_type(
			'mightycause/donation-form',
			array(
				'render_callback' => array( $this, 'render_donation_form_block' ),
				'attributes'      => array(
					'formId' => array(
						'type' => 'string',
					),
				),
			)
		);
	}

	/**
	 * Register REST API routes.
	 */
	public function register_rest_routes() {
		register_rest_route(
			'mightycause/v1',
			'/forms',
			array(
				'methods'  => 'GET',
				'callback' => array( $this, 'get_donation_forms' ),
			)
		);
	}

	/**
	 * Fetch donation forms and return as JSON.
	 *
	 * @return WP_REST_Response Response object.
	 */
	public function get_donation_forms() {
		$api   = new Mightycause_API();
		$token = get_option( 'mightycause_api_key' );
		$forms = $api->get_donation_forms( $token );

		if ( $forms === false ) {
			return new WP_REST_Response( array( 'error' => 'Failed to fetch forms.' ), 400 );
		}

		return new WP_REST_Response( $forms, 200 );
	}

	/**
	 * Render callback for the donation form block.
	 *
	 * @param array $attributes Block attributes.
	 * @return string HTML output for the block.
	 */
	public function render_donation_form_block( $attributes ) {
		if ( empty( $attributes['formId'] ) ) {
			return '<p>' . esc_html__( 'No widget selected.', 'mightycause-widgets' ) . '</p>';
		}

		$form_id = $attributes['formId'];
		$api     = new Mightycause_API();
		$forms   = $api->get_donation_forms( get_option( 'mightycause_api_key' ) );

		if ( ! $forms ) {
			return '<p>' . esc_html__( 'Widgets not found.', 'mightycause-widgets' ) . '</p>';
		}

		$form = null;
		foreach ( $forms as $f ) {
			if ( $f['short_token'] === $form_id ) {
				$form = $f;
				break;
			}
		}

		if ( ! $form ) {
			return '<p>' . esc_html__( 'Widget not found.', 'mightycause-widgets' ) . '</p>';
		}

		ob_start();
		?>
		<div class="mightycause-donation-form">
			<h2><?php echo esc_html( $form['label'] ); ?></h2>
			<iframe width="<?php echo esc_attr( $form['embed_width'] ); ?>" height="<?php echo esc_attr( $form['embed_height'] ); ?>"
				src="<?php echo esc_url( $form['iframe_url'] ); ?>" scrolling="no" marginheight="0" marginwidth="0" frameborder="0"></iframe>
		</div>
		<?php
		return ob_get_clean();
	}
}

new Mightycause_Blocks();
