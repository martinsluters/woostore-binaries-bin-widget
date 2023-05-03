<?php
/**
 * Binaries Bin Content List.
 *
 * @package WooStoreBinaryBinWidget
 */

defined( 'ABSPATH' ) || exit;

if ( ! empty( $binaries_bin ) && ! is_wp_error( $binaries_bin ) ) :
	?>
	<ul>
	<?php
	foreach ( $binaries_bin as $binary_key => $binary_value ) :
		?>
		<li>
			<pre><?php echo esc_html( $binary_key ); ?> : <?php echo esc_html( $binary_value ); ?></pre>
		</li>
		<?php
	endforeach;
	?>
	</ul>
	<?php
else :
	?>
	<p><?php echo esc_html__( 'Your Binary Bin is empty.', 'woo-store-binary-bin-widget' ); ?></p>
	<?php
endif;


