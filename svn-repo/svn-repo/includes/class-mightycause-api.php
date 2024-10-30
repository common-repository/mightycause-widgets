<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class for interacting with the Mightycause API.
 */
class Mightycause_API {

	/**
	 * Base URL for the Mightycause API.
	 *
	 * @var string
	 */
	private $api_url = 'https://www.mightycause.com/api/v4/embeds/wordpress?wordpress_token=';

	/**
	 * Fetch donation forms using the provided token.
	 *
	 * @param string $token API token.
	 * @return array|false Response from the API or false on failure.
	 */
	public function get_donation_forms( $token ) {
		$url      = $this->api_url . $token;
		$response = wp_remote_get( $url );

		if ( is_wp_error( $response ) ) {
			return false;
		}

		$forms = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( empty( $response['response']['code'] ) || 401 === $response['response']['code'] ) {
			return false;
		}

		return $forms;
	}
}
