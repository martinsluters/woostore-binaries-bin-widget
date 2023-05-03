<?php
/**
 * Binaries Bin Settings Form.
 *
 * @package WooStoreBinaryBinWidget
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woostore_binaries_bin_form' ); ?>

<form class="woocommerce-BinariesBinForm binary-binaries" action="" method="post" <?php do_action( 'woostore_binaries_bin_form_tag' ); ?> >

	<?php do_action( 'woostore_binaries_bin_form_start' ); ?>

	<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
		<label for="binaries_bin_settings"><?php esc_html_e( 'Binaries Bin Settings', 'woo-store-binaries-bin-widget' ); ?>&nbsp;<span class="required">*</span></label>
		<textarea class="woocommerce-Input woocommerce-Input--textarea input-textarea" name="binaries_bin_settings" id="binaries_bin_settings" rows="2" cols="5" placeholder="<?php esc_attr_e( 'JohnDoe', 'woo-store-binaries-bin-widget' ); ?>"><?php echo esc_textarea( $page->get_customer_binary_bin_settings_for_textarea() ); ?></textarea>
	</p>
	<div class="clear"></div>

	<?php do_action( 'woostore_binaries_bin_form' ); ?>

	<p>
		<?php wp_nonce_field( 'save_binaries_bin_settings', 'save-binaries-bin-settings-nonce' ); ?>
		<button type="submit" class="woocommerce-Button button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="save_binaries_bin_details" value="<?php esc_attr_e( 'Save changes', 'woocommerce' ); ?>"><?php esc_html_e( 'Save changes', 'woocommerce' ); ?></button>
		<input type="hidden" name="action" value="save_binaries_bin_settings" />
	</p>

	<?php do_action( 'woostore_binaries_bin_form_end' ); ?>
</form>

<?php do_action( 'woostore_after_binaries_bin_form' ); ?>
