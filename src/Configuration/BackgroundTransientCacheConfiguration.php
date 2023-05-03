<?php
/**
 * Background Trasient Cache (10up Async-Transients) related configuration for the dependency container.
 *
 * @package WooStoreBinaryBinWidget
 */

declare( strict_types=1 );

namespace martinsluters\WooStoreBinaryBinWidget\Configuration;

use \TenUp\AsyncTransients\Transient;
use martinsluters\WooStoreBinaryBinWidget\DependencyInjection\DependencyContainer;
use martinsluters\WooStoreBinaryBinWidget\DependencyInjection\ContainerConfigurationInterface;

/**
 * Background Trasient Cache (10up Async-Transients) related configuration for the dependency container.
 */
class BackgroundTransientCacheConfiguration implements ContainerConfigurationInterface {

	/**
	 * {@inheritdoc}
	 *
	 * @param DependencyContainer $container The container to modify.
	 */
	public function modify( DependencyContainer $container ) {
		$container['background_transient_cache'] = $container->service(
			function ( DependencyContainer $container ) {
				return Transient::instance();
			}
		);
	}
}
