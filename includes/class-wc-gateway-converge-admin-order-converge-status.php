<?php

class WC_Gateway_Converge_Admin_Order_Converge_Status {

	public function __construct() {

		add_action( 'woocommerce_admin_order_data_after_order_details', array(
			$this,
			'output_status'
		) );
	}

	/**
	 *
	 * @param WC_Order $order
	 */
	public function output_status() {
		global $theorder; // WC_Order object.
		$order    = $theorder;
		$order_id = $order->get_id();

		if ( ! $order instanceof WC_Order ) {
			return;
		}

		if ( $order->get_payment_method() != wgc_get_payment_name() ) {
			return; // This order is not a Converge order.
		}

		?>

        <div class="form-field form-field-wide wgc_sync_container">
			<div class="notice inline notice-warning">
				<p><?php _e( 'The subscription status is not updated. ', 'elavon-converge-gateway' ); ?>
					<a class="wgc_sync" href=""><?php _e( 'Sync with Converge now.', 'elavon-converge-gateway' ); ?></a>
				</p>
			</div>
		</div>

		<?php

		$converge_transaction_status = wgc_get_order_transaction_state( $order );

		if ( ! empty( $converge_transaction_status ) ) {
			echo '<p class="form-field form-field-wide"><label for=""> ' . __( 'Converge Transaction Status:', 'elavon-converge-gateway' ) . '</label><span>' . $converge_transaction_status->getValue() . '</span></p>';
		}

		$unique_transaction_id = get_post_meta( $order_id, '_unique_transaction_id', true );

		if ( ! empty( $unique_transaction_id ) ) {
			echo '<p class="form-field form-field-wide"><label for=""> ' . __( 'Merchant Transaction Code:', 'elavon-converge-gateway' ) . '</label><span>' . $unique_transaction_id . '</span></p>';
		}

	}

}

new WC_Gateway_Converge_Admin_Order_Converge_Status;