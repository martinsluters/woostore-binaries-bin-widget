<?php
/**
 * My Account > Tabs related configuration for the dependency container.
 *
 * @package WooStoreBinaryBinWidget
 */

declare( strict_types=1 );

namespace martinsluters\WooStoreBinaryBinWidget\Configuration;

use martinsluters\WooStoreBinaryBinWidget\MyAccount\BinariesBinTab;
use martinsluters\WooStoreBinaryBinWidget\DependencyInjection\DependencyContainer;
use martinsluters\WooStoreBinaryBinWidget\DependencyInjection\ContainerConfigurationInterface;

/**
 * My Account > Tabs related configuration for the dependency container.
 */
class MyAccountTabConfiguration implements ContainerConfigurationInterface {

	/**
	 * {@inheritdoc}
	 *
	 * @param DependencyContainer $container The container to modify.
	 */
	public function modify( DependencyContainer $container ) {
		$container['my-account:binaries-bin-tab'] = $container->service(
			function ( DependencyContainer $container ) {
				return new BinariesBinTab( $container['plugin_path'] . 'resources/templates/', $container['woocommerce.current_customer'], $container['binaries_bin_repository'] );
			}
		);
	}
}
