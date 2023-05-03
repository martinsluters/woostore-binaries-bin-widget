<?php
/**
 * WooCommerce My Account Binaries Bin tab.
 *
 * @package WooStoreBinaryBinWidget
 */

declare( strict_types=1 );

namespace martinsluters\WooStoreBinaryBinWidget\MyAccount;

use \WC_Customer;
use martinsluters\WooStoreBinaryBinWidget\BinariesBinRepository;

/**
 * WooCommerce My Account Binaries Bin tab class.
 */
class BinariesBinTab extends AbstractTab {

	/**
	 * The current WooCommerce customer.
	 *
	 * @var WC_Customer
	 */
	protected WC_Customer $current_customer;

	/**
	 * The binaries bin repository.
	 *
	 * @var BinariesBinRepository
	 */
	protected BinariesBinRepository $binaries_bin_repository;

	/**
	 * Constructor.
	 *
	 * @param string                $template_path The template path.
	 * @param WC_Customer           $current_customer The current WooCommerce customer.
	 * @param BinariesBinRepository $binaries_bin_repository The binaries bin repository.
	 */
	public function __construct( string $template_path, WC_Customer $current_customer, BinariesBinRepository $binaries_bin_repository ) {
		parent::__construct( $template_path );
		$this->current_customer        = $current_customer;
		$this->binaries_bin_repository = $binaries_bin_repository;
	}

	/**
	 * {@inheritdoc}
	 */
	public function myaccount_tab_slug(): string {
		return 'binaries-bin';
	}

	/**
	 * {@inheritdoc}
	 */
	public function myaccount_tab_title(): string {
		return esc_html__( 'Binaries Bin', 'woo-store-binary-bin-widget' );
	}

	/**
	 * Render settings form.
	 *
	 * @return void
	 */
	public function render_binaries_bin_settings_form_section() {
		wc_get_template( 'form-binaries-bin.php', array( 'page' => $this ), '', $this->template_path . 'woocommerce/myaccount/' );
	}

	/**
	 * Render binaries bin content wrapper.
	 *
	 * @return void
	 */
	public function render_binaries_bin_repository_content_section() {
		wc_get_template( 'binaries-bin-content.php', array(), '', $this->template_path . 'woocommerce/myaccount/' );
	}

	/**
	 * Render binaries bin content list.
	 *
	 * @return void
	 */
	public function render_binaries_bin_content_list_section() {
		$binaries_bin = $this->binaries_bin_repository->get_binaries();

		if ( is_wp_error( $binaries_bin ) ) {
			// translators: %s: HTTP transport service error message.
			echo esc_html( wp_sprintf( __( 'Error fetching binaries bin from http://httpbin.org/: %s', 'woo-store-binary-bin-widget' ), $binaries_bin->get_error_message() ) );
			return;
		}

		wc_get_template( 'binaries-bin-content-list.php', array( 'binaries_bin' => $binaries_bin ), '', $this->template_path . 'woocommerce/' );
	}

	/**
	 * Handle binaries bin settings form submission.
	 *
	 * @return void
	 */
	public function save_binaries_bin_settings() {

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$nonce_value = wc_get_var( $_REQUEST['save-binaries-bin-settings-nonce'], wc_get_var( $_REQUEST['_wpnonce'], '' ) );

		if ( ! wp_verify_nonce( $nonce_value, 'save_binaries_bin_settings' ) ) {
			return;
		}

		if ( empty( $_POST['action'] ) || 'save_binaries_bin_settings' !== $_POST['action'] ) {
			return;
		}

		wc_nocache_headers();

		// Flush previous notices.
		wc_clear_notices();

		// Do we have a user/customer at all? Bail quickly if not.
		if ( $this->current_customer->get_id() <= 0 ) {
			return;
		}

		$binaries_bin_settings = ! empty( $_POST['binaries_bin_settings'] ) ? sanitize_textarea_field( wp_unslash( $_POST['binaries_bin_settings'] ) ) : '';

		// Split by line.
		// "explode" might not work always.
		$binaries_bin_settings_array = preg_split( "/\r\n|\n|\r/", $binaries_bin_settings );

		// Remove empty ones.
		$binaries_bin_settings_array = array_filter(
			$binaries_bin_settings_array,
			function ( $single_setting_line ) {
				return ! empty( $single_setting_line );
			}
		);

		// Remove non-alphanumeric ones.
		$did_have_non_alphanumeric   = null;
		$binaries_bin_settings_array = array_filter(
			$binaries_bin_settings_array,
			function ( $single_setting_line ) use ( &$did_have_non_alphanumeric ) {
				$is_alphanumeric = 1 === preg_match( '/^[a-zA-Z0-9]+$/', $single_setting_line );
				if ( is_null( $did_have_non_alphanumeric ) || false === $did_have_non_alphanumeric ) {
					// Flag that there was a non-alphanumeric character to display notice.
					$did_have_non_alphanumeric = ! $is_alphanumeric;
				}
				return $is_alphanumeric;
			}
		);

		// Display notice if non-alphanumeric characters were removed.
		if ( true === $did_have_non_alphanumeric ) {
			wc_add_notice( __( 'The Binaries Bin settings contained non-alphanumeric characters. These have been removed.', 'woo-store-binary-bin-widget' ), 'error' );
		}

		// Save value in customers meta data.
		$this->save_customer_binaries_bin_settings( $binaries_bin_settings_array );

		// Pre-fetch binaries / background cache, to make sure items are available already.
		// Also resets the cache.
		$this->binaries_bin_repository->get_binaries( true );

		wc_add_notice( __( 'Binary bin settings changed successfully.', 'woo-store-binary-bin-widget' ) );

		wp_safe_redirect( wc_get_endpoint_url( $this->myaccount_tab_slug(), '', wc_get_page_permalink( 'myaccount' ) ) );
		exit;
	}

	/**
	 * Get customer binaries bin settings to be outputted in textarea.
	 *
	 * @return string
	 */
	public function get_customer_binary_bin_settings_for_textarea() {
		$settings = $this->current_customer->get_meta( 'binaries_bin_settings', true );

		if ( is_string( $settings ) ) {
			return $settings;
		}

		if ( ! is_array( $settings ) ) {
			return '';
		}

		return implode( PHP_EOL, $settings );
	}

	/**
	 * Save customer binaries bin settings.
	 *
	 * @param array $settings Settings to save.
	 * @return void
	 */
	protected function save_customer_binaries_bin_settings( $settings ) {
		$this->current_customer->update_meta_data( 'binaries_bin_settings', $settings );
		$this->current_customer->save_meta_data();
	}
}
