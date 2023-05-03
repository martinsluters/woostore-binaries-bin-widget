<?php
/**
 * WooCommerce My Account Binaries Bin Tab subscriber.
 *
 * @package WooStoreBinaryBinWidget
 */

declare( strict_types=1 );

namespace martinsluters\WooStoreBinaryBinWidget\Subscribers;

use martinsluters\WooStoreBinaryBinWidget\MyAccount\AbstractTab;
use martinsluters\WooStoreBinaryBinWidget\EventManagement\EventSubscriberInterface;

/**
 * WooCommerce My Account Binaries Bin Tab subscriber class.
 */
class BinariesBinMyAccountTabSubscriber implements EventSubscriberInterface {

	/**
	 * The my account tab to register.
	 *
	 * @var AbstractTab
	 */
	protected $tab;

	/**
	 * Constructor.
	 *
	 * @param AbstractTab $tab The new my account tab to register.
	 */
	public function __construct( AbstractTab $tab ) {
		$this->tab = $tab;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_subscribed_events(): array {
		return array(
			'init'                                      => 'register_binaries_bin_endpoint',
			'query_vars'                                => 'add_binary_bin_query_var',
			'woocommerce_account_menu_items'            => 'add_binaries_bin_tab',
			'woocommerce_account_binaries-bin_endpoint' => 'render_tab_content',
			'woostore_binaries_bin_tab_content'         => 'render_binaries_bin_tab_content',
			'woostore_binaries_bin_myaccount_content'   => 'render_binaries_bin_content_list_section',
			'template_redirect'                         => 'save_binaries_bin_settings',
		);
	}

	/**
	 * Render the binaries bin tab content.
	 *
	 * @return void
	 */
	public function render_binaries_bin_tab_content() {
		$this->render_binaries_bin_tab_form();
		$this->render_binaries_bin_tab_repository_content();
	}

	/**
	 * Render the binaries bin tab wrapper.
	 *
	 * @return void
	 */
	public function render_tab_content() {
		$this->tab->render_content();
	}

	/**
	 * Render the binaries bin tab settings form.
	 *
	 * @return void
	 */
	public function render_binaries_bin_tab_form() {
		$this->tab->render_binaries_bin_settings_form_section();
	}

	/**
	 * Add the binaries bin rewrite API endpoint.
	 *
	 * @return void
	 */
	public function register_binaries_bin_endpoint() {
		add_rewrite_endpoint( $this->tab->myaccount_tab_slug(), EP_ROOT | EP_PAGES );
	}

	/**
	 * Add the binaries bin WP query var.
	 *
	 * @param array $vars The query vars.
	 * @return array
	 */
	public function add_binary_bin_query_var( $vars ) {
		$vars[] = $this->tab->myaccount_tab_slug();
		return $vars;
	}

	/**
	 * Add the binaries bin tab to the my account tabs menu.
	 *
	 * @param array $items The existing my account tabs.
	 * @return array
	 */
	public function add_binaries_bin_tab( $items ) {
		$items[ $this->tab->myaccount_tab_slug() ] = esc_html( $this->tab->myaccount_tab_title() );
		return $items;
	}

	/**
	 * Handle the binaries bin settings form's submission.
	 *
	 * @return void
	 */
	public function save_binaries_bin_settings() {
		$this->tab->save_binaries_bin_settings();
	}

	/**
	 * Render the binaries bin content list.
	 *
	 * @return void
	 */
	public function render_binaries_bin_content_list_section() {
		$this->tab->render_binaries_bin_content_list_section();
	}

	/**
	 * Render the binaries bin repository content.
	 *
	 * @return void
	 */
	public function render_binaries_bin_tab_repository_content() {
		$this->tab->render_binaries_bin_repository_content_section();
	}
}
