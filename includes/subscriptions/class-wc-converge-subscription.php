<?php
defined( 'ABSPATH' ) || exit();

if ( ! class_exists( 'WC_Order' ) ) {
	return;
}

class WC_Converge_Subscription extends WC_Order {

	/**
	 *
	 * @var WC_Order
	 */
	public $order;

	public function __construct( $order = 0 ) {
		parent::__construct( $order );

		$this->order = wc_get_order( $this->get_parent_id() );
	}

	public function get_type() {
		return WGC_SUBSCRIPTION_POST_TYPE;
	}

	public function get_order( $id = 0 ) {
		return $this->order;
	}

	public function get_post() {
		return get_post( $this->get_id() );
	}

	public function get_view_subscription_url() {
		return wc_get_endpoint_url( 'view-converge-subscription', $this->id, wc_get_page_permalink( 'myaccount' ) );
	}

	public function get_change_subscription_payment_method_url() {
		return wc_get_endpoint_url( 'converge-subscription-change-method', $this->id, wc_get_page_permalink( 'myaccount' ) );
	}

	public function get_order_number() {
		return (string) apply_filters( 'woocommerce_order_number', $this->get_id(), $this );
	}

	public function is_cancellable() {
		$converge_subscription = wgc_get_gateway()->get_converge_api()->get_subscription( $this->get_transaction_id() );
		if ( ! $converge_subscription->isSuccess() ) {
			return false;
		} else {
			$converge_state = $converge_subscription->getSubscriptionState();
			if ( $converge_state->isCancelled() || $converge_state->isCompleted() || $converge_state->isUnpaid() ) {
				return false;
			} else {
				return ( $converge_subscription->getCancelAfterBillNumber() !== 0 && $this->get_status() != 'cancelled' );
			}
		}
	}

	public function get_order_item_totals( $tax_display = '' ) {
		$order_item_totals                           = parent::get_order_item_totals( $tax_display );
		$order_items                                 = $this->get_items( 'line_item' );
		$order_item_totals['order_total']['value']   = $this->get_formatted_order_total( $tax_display );
		$order_item_totals['cart_subtotal']['value'] = $this->get_formatted_line_subtotal( reset( $order_items ) );

		return $order_item_totals;
	}
	public function get_line_subtotal_shipping( $item, $inc_tax = false, $round = true ) {
		$subtotal      = parent::get_line_subtotal( $item, $inc_tax, $round );
			$subtotal += (float) $this->get_shipping_total();

		return $subtotal;
	}

	public function get_formatted_line_subtotal_shipping( $item, $tax_display = '' ) {
		$tax_display = $tax_display ? $tax_display : get_option( 'woocommerce_tax_display_cart' );

		if ( 'excl' === $tax_display ) {
			$ex_tax_label = $this->get_prices_include_tax() ? 1 : 0;

			$subtotal = wc_price(
				$this->get_line_subtotal_shipping( $item ),
				array(
					'ex_tax_label' => $ex_tax_label,
					'currency'     => $this->get_currency(),
				)
			);
		} else {
			$subtotal = wc_price( $this->get_line_subtotal_shipping( $item, true ), array( 'currency' => $this->get_currency() ) );
		}

		$product  = $item->get_product();
		$quantity = $item->get_quantity();
		if ( wgc_product_is_subscription( $product ) ) {
			$subtotal = sprintf(
				'%s %s',
				esc_html( $subtotal ),
				wp_kses_post( wgc_get_subscription_price_string( $product, $quantity, (float) $this->get_shipping_total() ) )
			);
		}

		return $subtotal;
	}
}
