<?php
/**
 * Plugin Name: WooStore Binary Bin Widget
 * Description: Binary Bin Widget for WooCommerce powered stores.
 * Plugin URI: https://github.com/martinsluters/woostore-binary-bin-widget
 * Author: Martins Luters
 * Author URI: https://github.com/martinsluters
 * Version: 1.0.0
 * License: MIT
 * Text Domain: woostore-binary-bin-widget
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
