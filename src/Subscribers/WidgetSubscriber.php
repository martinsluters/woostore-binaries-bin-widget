<?php
/**
 * Widgets event subscriber.
 *
 * @package WooStoreBinaryBinWidget
 */

declare( strict_types=1 );

namespace martinsluters\WooStoreBinaryBinWidget\Subscribers;

use \WP_Widget;
use martinsluters\WooStoreBinaryBinWidget\EventManagement\EventSubscriberInterface;

/**
 * Widgets event subscriber class.
 */
class WidgetSubscriber implements EventSubscriberInterface {

	/**
	 * The widget.
	 *
	 * @var WP_Widget
	 */
	protected $widget;

	/**
	 * Constructor.
	 *
	 * @param WP_Widget $widget The WP widget to subscribe.
	 */
	public function __construct( WP_Widget $widget ) {
		$this->widget = $widget;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_subscribed_events(): array {
		return array(
			'widgets_init' => 'register_widget',
		);
	}

	/**
	 * Register the widget.
	 *
	 * @return void
	 */
	public function register_widget() {
		register_widget( $this->widget );
	}
}
