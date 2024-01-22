<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

class WC_Meta_Box_Wgc_Coupon {

	public static function init() {
		add_action( 'woocommerce_coupon_options', array( __CLASS__, 'display_options' ), 10, 2 );
		add_action( 'woocommerce_coupon_options_save', array( __CLASS__, 'save' ), 10, 2 );
	}

	public static function display_options( $id, $coupon ) {
		include 'views/html-coupon.php';
	}

	public static function save( $post_id, $coupon ) {

		$props = array( wc_clean( $_POST['converge_subscription_type'] ) );

		foreach ( $props as $key => $value ) {
			update_post_meta( $post_id, '_converge_subscription_type', $value );
		}
	}
}

WC_Meta_Box_Wgc_Coupon::init();
