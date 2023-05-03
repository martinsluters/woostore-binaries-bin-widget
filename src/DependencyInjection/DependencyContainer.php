<?php
/**
 * Plugin Dependency Container.
 *
 * @package WooStoreBinaryBinWidget
 */

declare( strict_types=1 );

namespace martinsluters\WooStoreBinaryBinWidget\DependencyInjection;

use \WP_Error;
use \Closure;
use martinsluters\WooStoreBinaryBinWidget\DependencyInjection\ContainerConfigurationInterface;

/**
 * Plugin's Dependency Container.
 */
class DependencyContainer implements \ArrayAccess {

	/**
	 * Dependencies stored inside the container.
	 *
	 * @var array
	 */
	private array $dependencies;

	/**
	 * Constructor.
	 *
	 * @param array $dependencies The dependencies to store inside the container.
	 */
	public function __construct( array $dependencies = array() ) {
		$this->dependencies = $dependencies;
	}

	/**
	 * Configure the container using the given container configuration objects.
	 *
	 * @param array $configurations The container configuration objects.
	 */
	public function configure( array $configurations ) {
		foreach ( $configurations as $configuration ) {
			$this->modify( $configuration );
		}
	}

	/**
	 * Checks if there's a dependency in the container for the given key.
	 *
	 * @param mixed $key The key of the dependency to check.
	 *
	 * @return bool True if the dependency exists, false otherwise.
	 */
	public function offsetExists( $key ): bool {
		return array_key_exists( $key, $this->dependencies );
	}

	/**
	 * Get a dependency from the container.
	 *
	 * @param mixed $key The key of the dependency to get.
	 *
	 * @return mixed|WP_Error The dependency or a WP_Error if the dependency doesn't exist.
	 */
	public function offsetGet( $key ) : mixed {
		if ( ! array_key_exists( $key, $this->dependencies ) ) {
			return new WP_Error( 'no_value_found', sprintf( 'Container doesn\'t have a dependency stored for the "%s" key.', $key ) );
		}

		return $this->dependencies[ $key ] instanceof \Closure ? $this->dependencies[ $key ]( $this ) : $this->dependencies[ $key ];
	}

	/**
	 * Sets a dependency inside of the container.
	 *
	 * @param mixed $key The key of the dependency to set.
	 * @param mixed $value The service closure or anything else to set.
	 */
	public function offsetSet( $key, $value ): void {
		$this->dependencies[ $key ] = $value;
	}

	/**
	 * Unset the dependency in the container for the given key.
	 *
	 * @param mixed $key The key of dependency to unset.
	 */
	public function offsetUnset( $key ): void {
		unset( $this->dependencies[ $key ] );
	}

	/**
	 * Creates a closure used for creating a service using the given callable.
	 * The service will be instantiated only once and then cached.
	 *
	 * @param Closure $closure The callable used for creating the service.
	 *
	 * @return Closure
	 */
	public function service( Closure $closure ) {
		return function ( DependencyContainer $container ) use ( $closure ) {
			static $object;

			if ( null === $object ) {
				$object = $closure( $container );
			}

			return $object;
		};
	}

	/**
	 * Modify the container using the given container configuration object.
	 *
	 * @param mixed $configuration The container configuration object/string.
	 * @throws \InvalidArgumentException If the given configuration object doesn't implement the ContainerConfigurationInterface and is not string.
	 */
	private function modify( $configuration ) {
		if ( is_string( $configuration ) ) {
			$configuration = new $configuration();
		}

		if ( ! $configuration instanceof ContainerConfigurationInterface ) {
			throw new \InvalidArgumentException( 'Configuration object must implement the "ContainerConfigurationInterface".' );
		}

		$configuration->modify( $this );
	}
}
