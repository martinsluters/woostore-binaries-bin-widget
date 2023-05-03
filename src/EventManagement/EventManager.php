<?php
/**
 * Class responsible for registering WordPress actions and
 * filters from various subscriber instances.
 *
 * @package WooStoreBinaryBinWidget
 */

declare( strict_types=1 );

namespace martinsluters\WooStoreBinaryBinWidget\EventManagement;

/**
 * Class responsible for registering WordPress actions and filters from various subscriber instances.
 */
class EventManager {

	/**
	 * Adds a callback to a specific hook of the WordPress plugin API.
	 *
	 * @uses add_filter()
	 *
	 * @param string   $hook_name The name of the WordPress hook.
	 * @param callable $callback The callback to be executed when the hook is run.
	 * @param int      $priority The priority of the callback.
	 * @param int      $accepted_args The number of arguments that the callback accepts.
	 */
	public function add_callback( $hook_name, $callback, $priority = 10, $accepted_args = 1 ) {
		add_filter( $hook_name, $callback, $priority, $accepted_args );
	}

	/**
	 * Add an event subscriber.
	 *
	 * The event manager registers all the hooks that the given subscriber
	 * wants to register with the WordPress Plugin API.
	 *
	 * @param EventSubscriberInterface $subscriber The subscriber instance to register.
	 */
	public function add_subscriber( EventSubscriberInterface $subscriber ) {
		foreach ( $subscriber->get_subscribed_events() as $hook_name => $parameters ) {
			$this->add_subscriber_callback( $subscriber, $hook_name, $parameters );
		}
	}

	/**
	 * Executes all the functions registered with the hook with the given name.
	 *
	 * @param string $hook_name The name of the WordPress hook.
	 * @param mixed  $argument The argument passed to the functions.
	 */
	public function execute( $hook_name, $argument = null ) {
		// Remove $hook_name from the arguments.
		$arguments = array_slice( func_get_args(), 1 );

		// We use "do_action_ref_array" so that we can mock the function. This
		// isn't possible if we use "call_user_func_array" with "do_action".
		do_action_ref_array( $hook_name, $arguments );
	}

	/**
	 * Filters the given value by applying all the changes associated with the hook with the given name to
	 * the given value. Returns the filtered value.
	 *
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 *
	 * @param string $hook_name The name of the WordPress hook.
	 * @param mixed  $value The value to be filtered.
	 *
	 * @return mixed
	 */
	public function filter( $hook_name, $value ) {
		// Remove $hook_name from the arguments.
		$arguments = array_slice( func_get_args(), 1 );

		// We use "apply_filters_ref_array" so that we can mock the function. This
		// isn't possible if we use "call_user_func_array" with "apply_filters".
		return apply_filters_ref_array( $hook_name, $arguments );
	}

	/**
	 * Get the name of the hook that WordPress plugin API is executing. Returns
	 * false if it isn't executing a hook.
	 *
	 * @uses current_filter()
	 *
	 * @return string|bool
	 */
	public function get_current_hook() {
		return current_filter();
	}

	/**
	 * Checks the WordPress plugin API to see if the given hook has
	 * the given callback. The priority of the callback will be returned
	 * or false. If no callback is given will return true or false if
	 * there's any callbacks registered to the hook.
	 *
	 * @uses has_filter()
	 *
	 * @param string $hook_name The name of the WordPress hook.
	 * @param mixed  $callback The callback to check for.
	 *
	 * @return bool|int
	 */
	public function has_callback( $hook_name, $callback = false ) {
		return has_filter( $hook_name, $callback );
	}

	/**
	 * Removes the given callback from the given hook. The WordPress plugin API only
	 * removes the hook if the callback and priority match a registered hook.
	 *
	 * @uses remove_filter()
	 *
	 * @param string   $hook_name The name of the WordPress hook.
	 * @param callable $callback The callback to be removed.
	 * @param int      $priority The priority of the callback.
	 *
	 * @return bool
	 */
	public function remove_callback( $hook_name, $callback, $priority = 10 ) {
		return remove_filter( $hook_name, $callback, $priority );
	}

	/**
	 * Remove an event subscriber.
	 *
	 * The event manager removes all the hooks that the given subscriber
	 * wants to register with the WordPress Plugin API.
	 *
	 * @param EventSubscriberInterface $subscriber The subscriber instance to remove.
	 */
	public function remove_subscriber( EventSubscriberInterface $subscriber ) {
		foreach ( $subscriber->get_subscribed_events() as $hook_name => $parameters ) {
			$this->remove_subscriber_callback( $subscriber, $hook_name, $parameters );
		}
	}

	/**
	 * Adds the given subscriber's callback to a specific hook
	 * of the WordPress plugin API.
	 *
	 * @param EventSubscriberInterface $subscriber The subscriber instance to add callback to.
	 * @param string                   $hook_name The name of the WordPress hook.
	 * @param mixed                    $parameters The parameters of the hook.
	 */
	private function add_subscriber_callback( EventSubscriberInterface $subscriber, $hook_name, $parameters ) {
		if ( is_string( $parameters ) ) {
			$this->add_callback( $hook_name, array( $subscriber, $parameters ) );
		} elseif ( is_array( $parameters ) && isset( $parameters[0] ) ) {
			$this->add_callback( $hook_name, array( $subscriber, $parameters[0] ), isset( $parameters[1] ) ? $parameters[1] : 10, isset( $parameters[2] ) ? $parameters[2] : 1 );
		}
	}

	/**
	 * Removes the given subscriber's callback to a specific hook
	 * of the WordPress plugin API.
	 *
	 * @param EventSubscriberInterface $subscriber The subscriber instance to remove the callback from.
	 * @param string                   $hook_name The name of the WordPress hook.
	 * @param mixed                    $parameters The parameters of the hook.
	 */
	private function remove_subscriber_callback( EventSubscriberInterface $subscriber, $hook_name, $parameters ) {
		if ( is_string( $parameters ) ) {
			$this->remove_callback( $hook_name, array( $subscriber, $parameters ) );
		} elseif ( is_array( $parameters ) && isset( $parameters[0] ) ) {
			$this->remove_callback( $hook_name, array( $subscriber, $parameters[0] ), isset( $parameters[1] ) ? $parameters[1] : 10 );
		}
	}
}
