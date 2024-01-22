<?php
defined( 'ABSPATH' ) || exit();

class WGC_Form_Handler {

	public static function init() {
		add_action( 'wp_loaded', array( __CLASS__, 'change_payment_method' ) );
	}

	public static function change_payment_method() {
		if ( isset( $_POST['wgc_change_method_nonce'] ) && wp_verify_nonce( $_POST['wgc_change_method_nonce'],
				'change-payment-method' ) ) {
			$subscription = wc_get_order( wc_clean( $_POST['wgc_subscription_id'] ) );
			$payment_method = $_POST['payment_method'];

			/** @var WC_Gateway_Converge $gateway */
			$gateway = WC()->payment_gateways()->get_available_payment_gateways()[ $payment_method ];
			$result  = $gateway->change_subscription_payment_method( $subscription );
			if ( wc_notice_count( 'error' ) > 0 ) {
				return;
			} else {
				wc_add_notice( __( 'Your payment method has been updated.', 'elavon-converge-gateway' ), 'success' );
				wp_safe_redirect( wc_get_endpoint_url( 'view-converge-subscription',
					$subscription->get_id(),
					wc_get_page_permalink( 'myaccount' ) ) );
				exit();
			}
		}
	}
}
WGC_Form_Handler::init();
