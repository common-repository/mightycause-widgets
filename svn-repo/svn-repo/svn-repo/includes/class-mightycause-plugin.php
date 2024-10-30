<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main class for Mightycause Plugin.
 */
class Mightycause_Widgets {

	/**
	 * Instance of the class.
	 *
	 * @var null|Mightycause_Widgets
	 */
	private static $instance = null;

	/**
	 * Constructor method.
	 */
	private function __construct() {
		// Load dependencies.
		$this->load_dependencies();

		// Set up hooks.
		$this->setup_hooks();
	}

	/**
	 * Get instance of the class.
	 *
	 * @return Mightycause_Widgets
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Load dependencies.
	 */
	private function load_dependencies() {
		require_once MIGHTYCAUSE_PLUGIN_PATH . 'includes/class-mightycause-api.php';
		require_once MIGHTYCAUSE_PLUGIN_PATH . 'admin/class-mightycause-admin.php';
		require_once MIGHTYCAUSE_PLUGIN_PATH . 'blocks/class-mightycause-blocks.php';
	}

	/**
	 * Set up hooks.
	 */
	private function setup_hooks() {
		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_block_editor_assets' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
	}

	/**
	 * Activation callback.
	 */
	public function activate() {
		// Activation code here.
	}

	/**
	 * Deactivation callback.
	 */
	public function deactivate() {
		// Deactivation code here.
	}

	/**
	 * Add admin menu.
	 */
	public function admin_menu() {
		add_options_page(
			'Mightycause Widgets Settings',
			'Mightycause Widgets',
			'manage_options',
			'mightycause-settings',
			array( 'Mightycause_Admin', 'display_settings_page' )
		);
	}

	/**
	 * Initialize admin settings.
	 */
	public function admin_init() {
		register_setting( 'mightycause_options', 'mightycause_api_key' );
	}

	/**
	 * Enqueue block editor assets.
	 */
	public function enqueue_block_editor_assets() {
		wp_enqueue_script(
			'mightycause-block',
			MIGHTYCAUSE_PLUGIN_URL . 'js/block.js',
			array( 'wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-i18n', 'wp-api-fetch' ),
			filemtime( MIGHTYCAUSE_PLUGIN_PATH . 'block.js' )
		);

		wp_localize_script(
			'mightycause-block',
			'mightycauseBlockData',
			array(
				'restUrl' => esc_url_raw( rest_url( 'mightycause/v1/forms' ) ),
			)
		);
	}

	/**
	 * Enqueue admin assets.
	 */
	public function enqueue_admin_assets() {
		wp_enqueue_style(
			'mightycause-admin-style',
			MIGHTYCAUSE_PLUGIN_URL . 'css/admin.css',
			array(),
			filemtime( MIGHTYCAUSE_PLUGIN_PATH . 'css/admin.css' )
		);
	}
}


