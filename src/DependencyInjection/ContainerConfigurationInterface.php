<?php
/**
 * Plugin Dependency Container.
 *
 * @package WooStoreBinaryBinWidget
 */

declare( strict_types=1 );

namespace martinsluters\WooStoreBinaryBinWidget\DependencyInjection;

/**
 * A container configuration object configures a dependency injection container during the build process.
 */
interface ContainerConfigurationInterface {

	/**
	 * Modifies the given dependency injection container.
	 *
	 * @param DependencyContainer $container The container to modify.
	 */
	public function modify( DependencyContainer $container );
}
