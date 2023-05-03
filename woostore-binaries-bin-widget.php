<?php
/**
 * Plugin Name: WooStore Binaries Bin Widget
 * Description: Binaries Bin Widget for WooCommerce powered stores.
 * Plugin URI: https://github.com/martinsluters/woostore-binaries-bin-widget
 * Author: Martins Luters
 * Author URI: https://github.com/martinsluters
 * Version: 1.0.0
 * License: MIT
 * Text Domain: woo-store-binaries-bin-widget
 *
 * @package     WooStoreBinaryBinWidget
 * @author      Martins Luters (luters.martins@gmail.com)
 * @license     MIT
 *
 * @wordpress-plugin
 */

declare( strict_types=1 );

namespace martinsluters\WooStoreBinaryBinWidget;

defined( 'ABSPATH' ) || die();

if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
	require dirname( __FILE__ ) . '/vendor/autoload.php';
}

( Bootstrap::get_instance( __FILE__ ) )->init();
