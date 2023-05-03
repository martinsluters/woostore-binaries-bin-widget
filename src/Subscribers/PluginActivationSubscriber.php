<?php
/**
 * Plugin activation event subscriber.
 * Subscribe to WP events on the plugin's activation.
 *
 * @package WooStoreBinaryBinWidget
 */

declare( strict_types=1 );

namespace martinsluters\WooStoreBinaryBinWidget\Subscribers;

use martinsluters\WooStoreBinaryBinWidget\EventManagement\EventSubscriberInterface;

/**
 * Plugin activation event subscriber.
 */
class PluginActivationSubscriber implements EventSubscriberInterface {

	/**
	 * The plugin activation flag slug.
	 *
	 * @var string
	 */
	protected string $plugin_activation_flag_slug;

	/**
	 * Constructor.
	 *
	 * @param string $plugin_activation_flag_slug The plugin activation flag slug.
	 */
	public function __construct( string $plugin_activation_flag_slug ) {
		$this->plugin_activation_flag_slug = $plugin_activation_flag_slug;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_subscribed_events(): array {
		$activation_flag = get_option( $this->plugin_activation_flag_slug, true );

		if ( false === $activation_flag ) {
			return array(); // Nothing to subscribe to.
		}

		/**
		 * Indicate that the plugin is activated.
		 *
		 * @see https://developer.wordpress.org/reference/functions/register_activation_hook/#process-flow
		 */
		delete_option( $this->plugin_activation_flag_slug );

		return array(
			// Flush permalinks after binary bin my account page (tab) is registered.
			'init' => array( 'flush_permalinks', 50 ),
		);
	}

	/**
	 * Flush permalinks.
	 *
	 * @return void
	 */
	public function flush_permalinks() {
		flush_rewrite_rules();
	}
}
