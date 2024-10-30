<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class for managing the Mightycause plugin admin settings.
 */
class Mightycause_Admin {

	/**
	 * Display the settings page.
	 */
	public static function display_settings_page() {
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Mightycause Widgets Settings', 'mightycause-widgets' ); ?></h1>
			<p><?php esc_html_e( 'To use this plugin, you need a WordPress token from Mightycause. Please follow these steps to obtain your token:', 'mightycause-widgets' ); ?></p>
			<ol>
				<li><?php esc_html_e( 'Visit and log in to your Mightycause organization page.', 'mightycause-widgets' ); ?></li>
				<li><?php esc_html_e( 'Access the "Widgets" page from the left sidebar under "Fundraising Tools".', 'mightycause-widgets' ); ?></li>
				<li><?php esc_html_e( '"At the top right of the page, click "Get the WordPress plugin".', 'mightycause-widgets' ); ?></li>
				<li><?php esc_html_e( 'Click on "Continue".', 'mightycause-widgets' ); ?></li>
				<li><?php esc_html_e( 'Follow the instructions to paste the token in the field below and save the settings.', 'mightycause-widgets' ); ?></li>
			</ol>
			<p><?php esc_html_e( 'Once the token is saved, you will be able to use Gutenberg blocks for embedding Mightycause widgets directly into your posts and pages.', 'mightycause-widgets' ); ?></p>
			<form method="post" action="options.php">
				<?php
				settings_fields( 'mightycause_options' );
				do_settings_sections( 'mightycause-settings' );
				?>
				<table class="form-table">
					<tr valign="top">
						<th scope="row"><?php esc_html_e( 'Token', 'mightycause-widgets' ); ?></th>
						<td><input type="text" name="mightycause_api_key" value="<?php echo esc_attr( get_option( 'mightycause_api_key' ) ); ?>" /></td>
					</tr>
				</table>
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}
}

?>
