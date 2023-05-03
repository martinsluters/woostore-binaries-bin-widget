<?php
/**
 * WordPress Event (actions/filters) Management related configuration for the dependency container.
 *
 * @package WooStoreBinaryBinWidget
 */

declare( strict_types=1 );

namespace martinsluters\WooStoreBinaryBinWidget\Configuration;

use martinsluters\WooStoreBinaryBinWidget\EventManagement\EventManager;
use martinsluters\WooStoreBinaryBinWidget\DependencyInjection\DependencyContainer;
use martinsluters\WooStoreBinaryBinWidget\DependencyInjection\ContainerConfigurationInterface;
use martinsluters\WooStoreBinaryBinWidget\Subscribers;

/**
 * WordPress Event (actions/filters) Management related configuration for the dependency container.
 */
class EventManagementConfiguration implements ContainerConfigurationInterface {

	/**
	 * {@inheritdoc}
	 *
	 * @param DependencyContainer $container The container to modify.
	 */
	public function modify( DependencyContainer $container ) {

		$container['event_manager'] = $container->service(
			function ( DependencyContainer $container ) {
				return new EventManager();
			}
		);

		// Add all subscribers to events.
		$container['subscribers'] = $container->service(
			function ( DependencyContainer $container ) {
				$subscribers = array(
					new Subscribers\BinariesBinMyAccountTabSubscriber( $container['my-account:binaries-bin-tab'], $container['woocommerce.current_customer'] ),
					new Subscribers\WidgetSubscriber( $container['widgets.binary_bin'] ),
					new Subscribers\PluginActivationSubscriber( $container['plugin_activation_flag_slug'] ),
				);

				return $subscribers;
			}
		);
	}

}
