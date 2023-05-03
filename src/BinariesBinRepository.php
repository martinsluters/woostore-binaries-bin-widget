<?php
/**
 * Binaries Repository: Class BinariesBinRepository.
 *
 * @package WooStoreBinaryBinWidget
 */

namespace martinsluters\WooStoreBinaryBinWidget;

use \WC_Customer;
use \TenUp\AsyncTransients\Transient;
use martinsluters\WooStoreBinaryBinWidget\API\HTTPBinClient;

/**
 * Class that represents Binaries Bin Repository.
 */
class BinariesBinRepository {

	/**
	 * HTTPBin (http://httpbin.org/) API Client.
	 *
	 * @var HTTPBinClient $http_bin_api_client
	 */
	private HTTPBinClient $http_bin_api_client;

	/**
	 * Current Customer.
	 *
	 * @var WC_Customer $current_customer
	 */
	private WC_Customer $current_customer;

	/**
	 * Background Transient Cache
	 *
	 * @see https://github.com/10up/Async-Transients
	 * @var Transient $background_transient_cache
	 */
	private Transient $background_transient_cache;

	/**
	 * Transient (Object) Cache Key.
	 *
	 * @var string $cache_key
	 */
	private string $cache_key;

	/**
	 * Constructor.
	 *
	 * @param HTTPBinClient $http_bin_api_client HTTPBin API Client.
	 * @param WC_Customer   $current_customer Current WooCommerce Customer.
	 * @param Transient     $background_transient_cache Background Transient Cache.
	 */
	public function __construct( HTTPBinClient $http_bin_api_client, WC_Customer $current_customer, Transient $background_transient_cache ) {
		$this->http_bin_api_client        = $http_bin_api_client;
		$this->current_customer           = $current_customer;
		$this->background_transient_cache = $background_transient_cache;
		$this->cache_key                  = $this->current_customer->get_id() . '_binaries_bin_content';
	}

	/**
	 * Fetch Binaries Bin contents.
	 *
	 * @param array $binaries_bin_settings Customer's Binaries Bin settings.
	 * @return void
	 */
	public function fetch_binaries( $binaries_bin_settings ) {
		$binaries_bin_content = $this->http_bin_api_client->get_binaries_bin_contents( $binaries_bin_settings );

		if ( is_wp_error( $binaries_bin_content ) ) {
			$this->background_transient_cache->set( $this->cache_key, $binaries_bin_content, 1 ); // Avoid caching long if error.
			return;
		}

		$this->background_transient_cache->set( $this->cache_key, $binaries_bin_content, 5 * MINUTE_IN_SECONDS );
	}

	/**
	 * Get Binaries Bin contents.
	 * Return can be cached in static class method property or in Transient (Object) cache.
	 *
	 * @param boolean $force Force to get the Binaries Bin contents from the API.
	 * @return array|\WP_Error
	 */
	public function get_binaries( $force = false ) {
		static $binaries_bin_content_cached = null;

		if ( ! is_null( $binaries_bin_content_cached ) && ! $force ) {
			// Skip even going to the Transient cache (Object Cache if persistent caching enabled).
			// This is to avoid the Transient (Object) cache to be hit multiple times in the same request
			// if the same method is called multiple times.
			return $binaries_bin_content_cached;
		}

		$binaries_bin_settings = $this->current_customer->get_meta( 'binaries_bin_settings' );
		if ( ! is_array( $binaries_bin_settings ) || empty( $binaries_bin_settings ) ) {
			return new \WP_Error(
				'binaries_bin_settings_not_found',
				__( 'Binaries Bin settings not found.', 'woo-store-binaries-bin-widget' )
			);
		}

		if ( $force ) {
			$this->background_transient_cache->delete( $this->cache_key );
		}

		$binaries_bin_content_cached = $this->background_transient_cache
					->get( $this->cache_key, array( $this, 'fetch_binaries' ), array( $binaries_bin_settings, $force ) );

		return $binaries_bin_content_cached;
	}
}
