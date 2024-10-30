<?php
/**
 * Plugin Name: Mightycause Widgets
 * Plugin URI: https://www.mightycause.com/
 * Description: Integrates Mightycause fundraisers into your WordPress site.
 * Version: 1.0.0
 * Author: Mightycause
 * Author URI: https://www.mightycause.com/
 * License: GPL2
 * Text Domain: mightycause-widgets
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define plugin path.
define( 'MIGHTYCAUSE_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'MIGHTYCAUSE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Include necessary files.
require_once MIGHTYCAUSE_PLUGIN_PATH . 'includes/class-mightycause-plugin.php';

// Initialize the plugin.
add_action( 'plugins_loaded', array( 'Mightycause_Widgets', 'get_instance' ) );
