<?php
/**
 * Binaries Bin Content Wrapper.
 *
 * @package WooStoreBinaryBinWidget
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="woocommerce-binaries-bin-content">

		<h2 class="woocommerce-binaries-bin__title"><?php esc_html_e( 'Binaries Bin Content', 'woo-store-binaries-bin-widget' ); ?></h2>

		<?php do_action( 'woostore_binaries_bin_myaccount_content' ); ?>
</section>
