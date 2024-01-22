<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_Gateway_Converge_Admin_Order_Actions {

	public function __construct() {
		add_filter( 'woocommerce_order_actions', array(
			$this,
			'add_order_actions'
		) );
	}

	public function add_order_actions( $actions ) {
		/** @var WC_Order $theorder */
		global $theorder;

		wgc_get_gateway(); // Make sure Converge action is registered.
		do_action( 'wc_gateway_converge_order_converge_status_update', $theorder->get_id(), false );

		$actions = $this->add_void_action( $actions, $theorder );

		return $this->add_capture_action( $actions, $theorder );
	}

	protected function is_converge_order( $order ) {
		/** @var WC_Order $order */

		if (
			! $order instanceof WC_Order ||
			in_array( $order->get_status(), [ 'failed' ] ) ||
			$order->get_payment_method() != wgc_get_payment_name()
		) {
			return false;
		}

		$txn_id = $order->get_transaction_id();

		if ( empty( $txn_id ) ) {
			return false;
		}

		return true;
	}

	protected function add_void_action( $actions, $order ) {
		if ( ! $this->is_converge_order( $order ) ) {
			return $actions;
		}

		$transaction_state = wgc_get_order_transaction_state( $order );
		if ( ! $transaction_state || ! $transaction_state->isVoidable() ) {
			return $actions;
		}

		// Check refunded amount.
		$refunded_amount = wgc_get_gateway()->getConvergeOrderWrapper()->get_sale_transaction_refunded_amount( $order );
		$refunded_amount = ceil( (float) $refunded_amount );

		if ( ! $refunded_amount ) {
			$actions ['wgc_void_transaction'] = __( 'Void Transaction', 'elavon-converge-gateway' );
		}

		return $actions;
	}

	protected function add_capture_action( $actions, $order ) {
		if ( ! $this->is_converge_order( $order ) ) {
			return $actions;
		}

		$transaction_state = wgc_get_order_transaction_state( $order );
		if ( ! $transaction_state || ! $transaction_state->isCapturable() ) {
			return $actions;
		}

		$actions ['wgc_capture_transaction'] = __( 'Capture Authorized Transaction', 'elavon-converge-gateway' );

		return $actions;
	}

}

new WC_Gateway_Converge_Admin_Order_Actions;
