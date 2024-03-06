<?php
defined( 'ABSPATH' ) || exit();

class WC_Cart_Converge_Subscriptions {

	/**
	 * @var bool
	 */
	private $wgc_recurring_total_calculation = false;

	public function __construct() {
		add_filter( 'woocommerce_add_to_cart_handler', array( $this, 'add_to_cart_handler' ), 10, 2 );
		add_filter( 'woocommerce_cart_product_price', array( $this, 'cart_product_price' ), 10, 2 );
		add_filter( 'woocommerce_cart_product_subtotal', array( $this, 'cart_product_subtotal' ), 10, 3 );
		add_action( 'woocommerce_cart_totals_after_order_total', array( $this, 'display_recurring_totals_form' ) );
		add_action( 'woocommerce_converge-subscription_add_to_cart', 'woocommerce_simple_add_to_cart', 30 );
		add_action( 'woocommerce_converge-variable-subscription_add_to_cart', 'woocommerce_variable_add_to_cart', 30 );
		add_action( 'woocommerce_cart_needs_payment', array( $this, 'cart_needs_payment' ), 10, 2 );
		add_filter( 'woocommerce_order_formatted_line_subtotal', array( $this, 'order_product_price' ), 10, 3 );
		add_action( 'woocommerce_after_calculate_totals', array( $this, 'add_subscription_data' ), 20 );
	}

	public function cart_product_price( $price, $product ) {
		if ( wgc_product_is_subscription( $product ) ) {
			$price = wgc_get_product_price_html( $product, $price );
		}

		return $price;
	}

	public function cart_product_subtotal( $price, $product, $quantity ) {
		if ( wgc_product_is_subscription( $product ) ) {
			$price = wgc_get_product_price_html( $product, $price, $quantity );
		}

		return $price;
	}

	public function display_recurring_totals_form() {
		echo get_recurring_totals_form( 'cart' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	public function cart_needs_payment( $needs_payment, $cart ) {
		if ( wgc_has_subscription_elements_in_cart() ) {
			$needs_payment = true;
		}

		return $needs_payment;
	}

	public function order_product_price( $subtotal, $order_item, $order ) {
		$product = $order_item->get_product();
		if ( wgc_product_is_subscription( $product ) ) {
			$subtotal = wgc_get_product_price_html( $product, $subtotal, $order_item->get_quantity() );
		}

		return $subtotal;
	}

	public function add_to_cart_handler( $type, $product ) {

		if ( $type === WGC_VARIABLE_SUBSCRIPTION_NAME || $type === WGC_SUBSCRIPTION_VARIATION_NAME ) {
			$type = 'variable';
		}

		return $type;
	}

	/**
	 *
	 * @param WC_Cart $cart
	 */
	public function add_subscription_data( $cart ) {
		if ( $this->wgc_recurring_total_calculation ) {
			return;
		}
		$subscription_groups = array();

		WC()->cart->wgc_recurring_carts = array();
		$index                          = 0;
		foreach ( WC()->cart->get_cart() as $cart_key => $cart_item ) {
			if ( 'none' === $cart_item['data']->get_tax_status() ) {
				continue;
			}

			if ( wgc_product_is_subscription( $cart_item['data'] ) ) {
				$subscription_groups[] = $cart_key;
				++$index;
			}
		}
		foreach ( $subscription_groups as $recurring_cart_key => $subscription_group ) {
			$recurring_cart                    = clone WC()->cart;
			$recurring_cart->is_recurring_cart = true;
			$recurring_cart->cart_key          = $recurring_cart_key;

			foreach ( $recurring_cart->get_cart() as $cart_item_key => $recurring_cart_item ) {
				if ( $cart_item_key != $subscription_group ) {
					unset( $recurring_cart->cart_contents[ $cart_item_key ] );
				}
			}
			$this->wgc_recurring_total_calculation = true;
			$this->current_recurring_cart_key      = $recurring_cart_key;

			$recurring_cart->calculate_totals();

			WC()->cart->wgc_recurring_carts[ $subscription_group ] = $recurring_cart;
		}

		$this->wgc_recurring_total_calculation = false;
	}
}

new WC_Cart_Converge_Subscriptions();
