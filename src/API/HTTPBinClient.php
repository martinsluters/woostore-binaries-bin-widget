<?php
/**
 * HTTPBin (http://httpbin.org/) API Client.
 *
 * @package WooStoreBinaryBinWidget
 */

declare( strict_types=1 );

namespace martinsluters\WooStoreBinaryBinWidget\API;

use \WP_Http;
use \WP_Error;

/**
 * HTTPBin (http://httpbin.org/) API Client class.
 */
class HTTPBinClient {

	/**
	 * Base URL for of HTTPBin API routes.
	 *
	 * @var string
	 */
	const ENDPOINT_BASE = 'https://httpbin.org';

	/**
	 * The WordPress HTTP transport.
	 *
	 * @var WP_Http
	 */
	private WP_Http $http_transport;

	/**
	 * Constructor.
	 *
	 * @param WP_Http $http_transport The WordPress HTTP transport.
	 */
	public function __construct( WP_Http $http_transport ) {
		$this->http_transport = $http_transport;
	}

	/**
	 * Get the contents of the HTTPBin API /post endpoint (POST).
	 *
	 * @param array $post_data The data to post.
	 * @return array|WP_Error
	 */
	public function get_binaries_bin_contents( array $post_data ) {
		$response = $this->http_transport->post(
			self::ENDPOINT_BASE . '/post',
			array(
				'timeout'  => 10,
				'compress' => true,
				'body'     => wp_json_encode( $post_data ),
				'headers'  => array(
					'Content-Type' => 'application/json',
				),
			)
		);

		if ( $response instanceof WP_Error ) {
			return $response;
		}

		$response_status_code = isset( $response['response']['code'] ) && 200 === $response['response']['code'] ? 200 : false;

		if ( false === $response_status_code ) {
			return new WP_Error(
				'binary_bin_api_error',
				__( 'Error while retrieving data from HTTPBin API.', 'woo-store-binaries-bin-widget' )
			);
		}

		return $response['headers']->getAll();
	}
}
