<?php
/**
 * HTTPBin (http://httpbin.org/) API related configuration for the dependency container.
 *
 * @package WooStoreBinaryBinWidget
 */

declare( strict_types=1 );

namespace martinsluters\WooStoreBinaryBinWidget\Configuration;

use martinsluters\WooStoreBinaryBinWidget\API\HTTPBinClient;
use martinsluters\WooStoreBinaryBinWidget\DependencyInjection\DependencyContainer;
use martinsluters\WooStoreBinaryBinWidget\DependencyInjection\ContainerConfigurationInterface;

/**
 * HTTPBin (http://httpbin.org/) API related configuration for the dependency container.
 */
class HTTPBinAPIClientConfiguration implements ContainerConfigurationInterface {

	/**
	 * {@inheritdoc}
	 *
	 * @param DependencyContainer $container The container to modify.
	 */
	public function modify( DependencyContainer $container ) {
		$container['http_bin_api_client'] = $container->service(
			function ( DependencyContainer $container ) {
				return new HTTPBinClient( $container['wordpress.http_transport'] );
			}
		);
	}
}
