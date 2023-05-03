<?php
/**
 * Binaries Bin Repository related configuration for the dependency container.
 *
 * @package WooStoreBinaryBinWidget
 */

declare( strict_types=1 );

namespace martinsluters\WooStoreBinaryBinWidget\Configuration;

use martinsluters\WooStoreBinaryBinWidget\BinariesBinRepository;
use martinsluters\WooStoreBinaryBinWidget\DependencyInjection\DependencyContainer;
use martinsluters\WooStoreBinaryBinWidget\DependencyInjection\ContainerConfigurationInterface;

/**
 * Binaries Bin Repository related configuration for the dependency container.
 */
class BinariesBinRepositoryConfiguration implements ContainerConfigurationInterface {

	/**
	 * {@inheritdoc}
	 *
	 * @param DependencyContainer $container The container to modify.
	 */
	public function modify( DependencyContainer $container ) {
		$container['binaries_bin_repository'] = $container->service(
			function ( DependencyContainer $container ) {
				return new BinariesBinRepository( $container['http_bin_api_client'], $container['woocommerce.current_customer'], $container['background_transient_cache'] );
			}
		);
	}
}
