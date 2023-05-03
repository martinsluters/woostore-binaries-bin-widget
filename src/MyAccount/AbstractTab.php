<?php
/**
 * Abstract WooCommerce My Account tab.
 *
 * @package WooStoreBinaryBinWidget
 */

declare( strict_types=1 );

namespace martinsluters\WooStoreBinaryBinWidget\MyAccount;

/**
 * Abstract WooCommerce My Account tab class.
 */
abstract class AbstractTab {

	/**
	 * Path to the WooCommerce My Account tab templates.
	 *
	 * @var string
	 */
	protected string $template_path;

	/**
	 * Constructor.
	 *
	 * @param string $template_path The template path.
	 */
	public function __construct( string $template_path ) {
		$this->template_path = $template_path;
	}

	/**
	 * Get the tab slug.
	 *
	 * @return void
	 */
	public function render_content(): void {
		wc_get_template( 'tab-' . $this->myaccount_tab_slug() . '.php', array( 'page' => $this ), '', $this->template_path . 'woocommerce/myaccount/' );
	}

	/**
	 * Get the tab slug.
	 *
	 * @return string
	 */
	abstract public function myaccount_tab_slug(): string;

	/**
	 * Get the tab title.
	 *
	 * @return string
	 */
	abstract public function myaccount_tab_title(): string;
}
