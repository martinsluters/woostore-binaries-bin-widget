<?php
/**
 * Plugin bootstrap file.
 *
 * @package WooStoreBinaryBinWidget
 */

declare( strict_types=1 );

namespace martinsluters\WooStoreBinaryBinWidget;

use martinsluters\WooStoreBinaryBinWidget\DependencyInjection\DependencyContainer;

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
	 * The plugin's dependency injection container.
	 *
	 * @var DependencyContainer
	 */
	private DependencyContainer $container;

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
		// Load the plugin's dependency injection container with some initial dependencies.
		$this->container = new DependencyContainer(
			array(
				'main_plugin_file' => $main_plugin_file,
				'plugin_path'      => plugin_dir_path( $main_plugin_file ),
				'plugin_version'   => self::VERSION,
				'plugin_slug'      => self::SLUG,
			)
		);
		$this->loaded    = false;
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
	 * Main bootstrap method.
	 *
	 * @return void
	 */
	public function init(): void {
		add_action( 'after_setup_theme', array( $this, 'bootstrap_core' ) );
	}

	/**
	 * Bootstrap the heart of the plugin.
	 *
	 * @return void
	 */
	public function bootstrap_core() {
		// Only load once.
		if ( $this->loaded ) {
			return;
		}

		// Configure the plugin's dependency injection container with the rest of the dependencies.
		$this->container->configure(
			array(
				Configuration\BackgroundTransientCacheConfiguration::class,
				Configuration\WordPressConfiguration::class,
				Configuration\HTTPBinAPIClientConfiguration::class,
				Configuration\WooCommerceConfiguration::class,
				Configuration\EventManagementConfiguration::class,
			)
		);

		$this->loaded = true;
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
