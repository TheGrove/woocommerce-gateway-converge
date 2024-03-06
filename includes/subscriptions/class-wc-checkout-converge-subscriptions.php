<?php
defined( 'ABSPATH' ) || exit();


class WC_Checkout_Converge_Subscriptions {

	public function __construct() {
		add_action( 'wgc_checkout_process_payment', array( __CLASS__, 'process_checkout' ) );
		add_filter( 'user_has_cap', array( __CLASS__, 'restrict_guest_checkout' ), 9999, 3 );
		add_filter( 'before_woocommerce_pay', array( $this, 'product_types_validator' ), 10, 1 );
		add_filter( 'woocommerce_before_checkout_form', array( $this, 'product_types_validator' ), 10, 1 );
		add_action( 'woocommerce_review_order_after_order_total', array( $this, 'display_recurring_totals_form' ) );
	}

	public static function process_checkout( $order ) {
		if ( ! $order instanceof WC_Order || ! $order->needs_payment() ) {
			return;
		}

		$order_from_merchant_view = isset( $_GET['pay_for_order'] ) && isset( $_GET['key'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		if ( $order_from_merchant_view ) {
			if ( ! wgc_has_subscription_elements_in_order( $order ) ) {
				return;
			}
		} elseif ( ! wgc_has_subscription_elements_in_cart() ) {
			return;
		}

		$old_subscriptions = wgc_get_subscriptions_for_order( $order );
		foreach ( (array) $old_subscriptions as $old_subscription ) {
			wp_delete_post( $old_subscription->get_id(), true );
		}

		if ( $order_from_merchant_view ) {
			$subscription_order_elements = wgc_get_only_subscription_elements_from_order( $order );
			$order_cart                  = new WC_Cart();
			foreach ( $subscription_order_elements as $subscription_element ) {
				$order_cart->add_to_cart( $subscription_element->get_id() );
			}
			$subscription_elements = wgc_get_only_subscription_elements_from_cart( $order_cart->get_cart() );
		} else {
			$subscription_elements = wgc_get_only_subscription_elements_from_cart();
		}
		foreach ( $subscription_elements as $recurring_cart_key => $subscription_element ) {

			if ( $order_from_merchant_view ) {
				$recurring_cart = clone $order_cart;
			} else {
				$recurring_cart = clone WC()->cart;
			}
			$recurring_cart->cart_key = $recurring_cart_key;
			$subscription_cart_item   = null;

			foreach ( $recurring_cart->get_cart() as $cart_item_key => $recurring_cart_item ) {

				if ( $recurring_cart_key != $cart_item_key ) {
					unset( $recurring_cart->cart_contents[ $cart_item_key ] );
				} else {
					$subscription_cart_item = $recurring_cart_item;
				}
			}

			if ( ! is_null( $subscription_cart_item ) ) {
				self::create_subscription( $order, $recurring_cart, $subscription_cart_item, $recurring_cart_key );
			}
		}

		$order->save();
	}

	public static function create_subscription( $order, $recurring_cart, $subscription_cart_item, $recurring_cart_key ) {

		$subscription_product = $subscription_cart_item['data'];
		$subscription_data    = array(
			'order_id'       => $order->get_id(),
			'customer_user'  => $order->get_user_id(),
			'order_currency' => $order->get_currency(),
			'order_note'     => $order->get_customer_note(),
		);
		$subscription         = wgc_create_subscription( $subscription_data );

		if ( is_wp_error( $subscription ) ) {
			throw new Exception( $subscription->get_error_message() ); // phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped
		}

		$subscription = wgc_assign_billing_and_shipping_to_subscription( $order, $subscription );

		$coupon_type = wgc_get_coupon_type( $recurring_cart );
		$subscription->update_meta_data( 'wgc_coupon_type', $coupon_type );

		if ( 'single' == $coupon_type ) {
			$recurring_cart->remove_coupons();
		} else {
			WC()->checkout()->create_order_coupon_lines( $subscription, $recurring_cart );
			$subscription->set_discount_total( $recurring_cart->get_cart_discount_total() );
			$subscription->set_discount_tax( $recurring_cart->get_cart_discount_tax_total() );
		}

		$recurring_cart->calculate_totals();

		WC()->checkout()->create_order_line_items( $subscription, $recurring_cart );
		WC()->checkout()->create_order_fee_lines( $subscription, $recurring_cart );

		$original_order_shipping_items = $order->get_items( 'shipping' );

		foreach ( (array) $original_order_shipping_items as $original_order_shipping_item ) {
			$item_id = wc_add_order_item(
				$subscription->get_id(),
				array(
					'order_item_name' => $original_order_shipping_item['name'],
					'order_item_type' => 'shipping',
				)
			);
			if ( $item_id ) {
				wc_add_order_item_meta( $item_id, 'method_id', $original_order_shipping_item['method_id'] );
				wc_add_order_item_meta( $item_id, 'cost', wc_format_decimal( $original_order_shipping_item['cost'] ) );
			}
		}

		WC()->checkout()->create_order_tax_lines( $subscription, $recurring_cart );

		$gateways = WC()->payment_gateways()->get_available_payment_gateways();

		if ( isset( $gateways[ $order->get_payment_method() ] ) ) {
			$subscription->set_payment_method( $gateways[ $order->get_payment_method() ] );
		}

		$subscription->set_shipping_total( $recurring_cart->shipping_total );
		$subscription->set_cart_tax( $recurring_cart->tax_total );
		$subscription->set_shipping_tax( $recurring_cart->shipping_tax_total );
		$subscription->set_total( $recurring_cart->total );
		$subscription->update_meta_data( 'wgc_subscription_product_qty', $subscription_cart_item['quantity'] );
		$subscription->save();
		$subscription->calculate_totals();

		/** @var \Elavon\Converge2\Response\OrderResponse $subscription_plan_response */
		$subscription_plan_response = wgc_get_gateway()->get_converge_api()->create_subscription_plan( $subscription, $subscription_product );

		if ( $subscription_plan_response->isSuccess() ) {
			$subscription->update_meta_data( 'wgc_plan_id', $subscription_plan_response->getId() );
		} else {
			$subscription->add_order_note(
				wgc_get_order_error_note(
					__( 'Could not create subscription plan.', 'elavon-converge-gateway' ),
					$subscription_plan_response
				)
			);
			$subscription->update_status( 'failed' );
		}

		$subscription->save();

		return $subscription;
	}

	public function product_types_validator( $checkout = '' ) {
		if ( ! wgc_has_subscription_elements_in_cart() && ! wgc_order_from_merchant_view_has_subscription_elements() ) {
			return;
		} elseif ( true === $checkout->enable_guest_checkout ) {
				$checkout->enable_guest_checkout = false;
				$checkout->must_create_account   = true;
		}

		$order_from_merchant_view = isset( $_GET['pay_for_order'] ) && isset( $_GET['key'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( $order_from_merchant_view ) {
			$order_id = wc_get_order_id_by_order_key( wc_clean( wp_unslash( $_GET['key'] ) ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( $order = wc_get_order( $order_id ) ) {
				$products = $order->get_items();
			}
		} else {
			$products = WC()->cart->get_cart();
		}

		foreach ( $products as $product ) {

			if ( ! $order_from_merchant_view ) {
				$product = $product['data'];
			} else {
				$product = $product->get_product();
			}

			if ( ! wgc_is_product_compatible_with_subscription( $product ) ) {
				$product = wgc_get_product( $product );
				if ( $order_from_merchant_view ) {
					$notice = sprintf(
						__(
							'%1$s is not compatible with the other subscription products from your order. Please contact the merchant before trying again.',
							'elavon-converge-gateway'
						),
						$product->get_name()
					);
					wc_add_notice( $notice, 'error' );
					wc_print_notices();
				} else {
					$notice = sprintf(
						__(
							'%1$s is not compatible with the other subscription products from your cart. Please edit your cart and try again.',
							'elavon-converge-gateway'
						),
						$product->get_name()
					);

					wc_add_notice( $notice, 'error' );
					wp_safe_redirect( wc_get_page_permalink( 'cart' ) );
				}

				exit;
			}

			if ( wgc_product_is_subscription( $product ) ) {
				/** @var WC_Product_Converge_Subscription $product */
				$product               = wgc_get_product( $product );
				$converge_product_plan = wgc_get_gateway()->get_converge_api()->get_plan( $product->get_wgc_plan_id() );
				if ( ! $converge_product_plan->isSuccess() ) {
					$notice = __(
						'Some of your subscription data is unavailable at this time. Please try again later.',
						'elavon-converge-gateway'
					);
					wc_add_notice( $notice, 'error' );

					if ( $order_from_merchant_view ) {
						wc_print_notices();
					} else {
						wp_safe_redirect( wc_get_page_permalink( 'cart' ) );
					}
					exit;
				}
			}
		}
	}

	public static function restrict_guest_checkout( $allcaps, $caps, $args ) {
		if ( is_user_logged_in() ) {
			return $allcaps;
		}

		if ( isset( $caps[0], $_GET['key'], $args[2] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( $caps[0] == 'pay_for_order' ) {
				$order_id = $args[2];
				if ( wgc_order_id_from_merchant_view_has_subscription_elements( $order_id ) ) {
					unset( $allcaps['pay_for_order'] );
				}
			}
		}
		return $allcaps;
	}

	public function display_recurring_totals_form() {
		echo get_recurring_totals_form( 'checkout' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

new WC_Checkout_Converge_Subscriptions();
