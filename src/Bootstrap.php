<?php
/**
 * Plugin bootstrap file.
 *
 * @package WooStoreBinaryBinWidget
 */

declare( strict_types=1 );

namespace martinsluters\WooStoreBinaryBinWidget;

/**
 * Bootstrap plugin.
 */
final class Bootstrap {

	/**
	 * Slug of the plugin.
	 *
	 * @var string
	 */
	const SLUG = 'woo-store-binary-bin-widget';

	/**
	 * Plugin version.
	 *
	 * @var string
	 */
	const VERSION = '1.0.0';

	/**
	 * Stores one and only true instance of Bootstrap
	 *
	 * @var self
	 */
	private static ?Bootstrap $instance = null;

	/**
	 * Flag to track if the plugin is loaded.
	 *
	 * @var bool
	 */
	private $loaded;

	/**
	 * Private constructor.
	 *
	 * @param string $main_plugin_file The main plugin file.
	 */
	private function __construct( $main_plugin_file ) {

	}

	/**
	 * Prevent the instance from being cloned (which would create a second instance of it)
	 *
	 * @return void
	 */
	private function __clone(): void {
	}

	/**
	 * Prevent from being unserialized (which would create a second instance of it).
	 *
	 * @return void
	 * @throws \Exception Throw new Exception.
	 */
	public function __wakeup(): void {
		throw new \Exception( 'Cannot unserialize singleton' );
	}

	/**
	 * Singleton instantiation.
	 *
	 * @param string $main_plugin_file String containing the main plugin file path.
	 * @return self
	 */
	public static function get_instance( string $main_plugin_file ): self {
		if ( null === self::$instance ) {
			self::$instance = new self( $main_plugin_file );
		}
		return self::$instance;
	}

	/**
	 * Tear down function for Unit Testing singleton.
	 *
	 * @return void
	 */
	public static function tear_down(): void {
		static::$instance = null;
	}
}
