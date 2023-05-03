<?php
/**
 * Widgets related configuration for the dependency container.
 *
 * @package WooStoreBinaryBinWidget
 */

declare( strict_types=1 );

namespace martinsluters\WooStoreBinaryBinWidget\Configuration;

use martinsluters\WooStoreBinaryBinWidget\Widget;
use martinsluters\WooStoreBinaryBinWidget\DependencyInjection\DependencyContainer;
use martinsluters\WooStoreBinaryBinWidget\DependencyInjection\ContainerConfigurationInterface;

/**
 * Widgets related configuration for the dependency container.
 */
class WidgetConfiguration implements ContainerConfigurationInterface {

	/**
	 * {@inheritdoc}
	 *
	 * @param DependencyContainer $container The container to modify.
	 */
	public function modify( DependencyContainer $container ) {
		$container['widgets.binary_bin'] = $container->service(
			function ( DependencyContainer $container ) {
				return new Widget\BinaryBinWidget( $container['binaries_bin_repository'], $container['woocommerce.current_customer'], $container['plugin_path'] . 'resources/templates/' );
			}
		);
	}
}
