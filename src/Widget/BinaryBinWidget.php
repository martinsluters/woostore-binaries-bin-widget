<?php
/**
 * Binary Bin Widget.
 *
 * @package WooStoreBinaryBinWidget
 */

declare( strict_types=1 );

namespace martinsluters\WooStoreBinaryBinWidget\Widget;

use \WP_Widget;
use \WC_Customer;
use martinsluters\WooStoreBinaryBinWidget\BinariesBinRepository;

/**
 * Binary Bin Widget.
 */
class BinaryBinWidget extends WP_Widget {

	/**
	 * The binaries bin repository.
	 *
	 * @var BinariesBinRepository
	 */
	protected BinariesBinRepository $binaries_bin_repository;

	/**
	 * The current customer.
	 *
	 * @var WC_Customer
	 */
	protected WC_Customer $current_customer;

	/**
	 * Path to the templates.
	 *
	 * @var string
	 */
	protected string $template_path;

	/**
	 * Constructor.
	 *
	 * @param BinariesBinRepository $binaries_bin_repository The binaries bin repository.
	 * @param WC_Customer           $current_customer The current WordPress customer.
	 * @param string                $template_path The path to the templates.
	 */
	public function __construct( BinariesBinRepository $binaries_bin_repository, WC_Customer $current_customer, string $template_path ) {
		$this->binaries_bin_repository = $binaries_bin_repository;
		$this->current_customer        = $current_customer;
		$this->template_path           = $template_path;
		parent::__construct(
			'woo-store-binary-bin-widget',
			'Woo Store Binary Bin Widget',
			array(
				'description' => 'Woo Store Binary Bin Widget',
			)
		);
	}

	/**
	 * Render the widget.
	 *
	 * @param array $args The widget arguments.
	 * @param array $instance The widget instance.
	 */
	public function widget( $args, $instance ) {
		echo '<div class="widget widget_woo_store_binary_bin_widget">';
		echo '<h2 class="widget-title">' . esc_html__( 'Binary Bin Contents > Widget', 'woo-store-binary-bin-widget' ) . '</h2>';
		echo '<div class="widget-content">';
		$this->get_content();
		echo '</div>';
		echo '</div>';
	}

	/**
	 * Outputs the widget contents.
	 *
	 * @return void.
	 */
	protected function get_content(): void {
		if ( 0 >= $this->current_customer->get_id() ) {
			echo '<p>' . esc_html__( 'Please log in to see your Binary Bin contents.', 'woo-store-binary-bin-widget' ) . '</p>';
			return;
		}

		$binaries_bin = $this->binaries_bin_repository->get_binaries();

		wc_get_template( 'binaries-bin-content-list.php', array( 'binaries_bin' => $binaries_bin ), '', $this->template_path . 'woocommerce/' );
	}
}
