<?php

use Elavon\Converge2\DataObject\OrderItemType;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Generates requests to send to Converge.
 */
class WC_Gateway_Converge_Order_Wrapper {

	/**
	 * Stores line items.
	 *
	 * @var array
	 */
	protected $line_items = array();

	/**
	 * Stores order items (including tax and shipping) for Converge.
	 *
	 * @var array
	 */
	private $order_items = array();


	/**
	 * Pointer to gateway making the request.
	 *
	 * @var WC_Gateway_Converge
	 */
	protected $gateway;

	/**
	 * Endpoint for requests to Converge.
	 *
	 * @var string
	 */
	protected $endpoint;

	/**
	 * Constructor.
	 *
	 * @param WC_Gateway_Converge $gateway Converge gateway object.
	 */
	public function __construct( $gateway ) {
		$this->gateway = $gateway;
	}

	/**
	 * Get the Converge request URL for an order.
	 *
	 * @param string Converge 2 payment session id
	 * @param bool $sandbox Whether to use sandbox mode or not.
	 *
	 * @return string
	 */
	public function get_request_url( $payment_session_id, $hpp_url) {

		$this->endpoint = $hpp_url;
		$converge_args  = array(
			'merchantAlias' => $this->gateway->get_option( WGC_KEY_MERCHANT_ALIAS ),
			'publicApiKey'  => $this->gateway->get_option( WGC_KEY_PUBLIC_KEY ),
			'sessionId'     => $payment_session_id,
		);

		return $this->endpoint . '?' . http_build_query( $converge_args, '', '&' );
	}


	/**
	 * @param $order
	 *
	 * @return array
	 */
	public function get_order_items( $order ) {
		if ( ! $this->order_items ) {
			$this->set_order_items( $order );
		}

		return $this->order_items;
	}

	/**
	 * @param $order
	 *
	 * @return $this
	 */
	public function set_order_items( $order ) {
		$line_itmes = $this->get_line_item_args( $order );

		// Add Cart Items to Order Items
		foreach ( $line_itmes['cart'] as $item ) {
			$this->add_order_item( wgc_create_order_item( $item['item_name'], $item['amount'], $item['quantity'], OrderItemType::UNKNOWN ) );
		}

		// Add Tax to Order Items
		if ( array_key_exists( 'tax_cart', $line_itmes ) && $line_itmes['tax_cart'] > 0 ) {
			$this->add_order_item( wgc_create_order_item( __( 'Tax', 'elavon-converge-gateway' ), $line_itmes['tax_cart'], 1, OrderItemType::TAX ) );
		}

		// Add Shipping to Order Items
		if ( array_key_exists( 'shipping', $line_itmes ) && $line_itmes['shipping'] > 0 ) {
			$shipping_name  = sprintf( __( 'Shipping via %s', 'elavon-converge-gateway' ), $order->get_shipping_method() );
			$shipping_price = wgc_number_format( wgc_round( $order->get_shipping_total() + $order->get_shipping_tax() ) );
			$this->add_order_item( wgc_create_order_item( $shipping_name, $shipping_price, 1, OrderItemType::SHIPPING ) );
		}

		return $this;
	}

	/**
	 * @param $item
	 */
	public function add_order_item( $item ) {
		$this->order_items[] = $item;
	}

	/**
	 * Get billing args for converge request.
	 *
	 * @param WC_Order $order Order object.
	 *
	 * @return array
	 */
	public function get_billing_args( $order ) {
		return wgc_get_order_bill_to( $order );
	}


	/**
	 * Get shipping for converge request.
	 *
	 * @param WC_Order $order Order object.
	 *
	 * @return array
	 */
	public function get_ship_to( $order ) {
		return wgc_get_order_ship_to( $order );
	}

	/**
	 * Get shipping cost line item args for converge request.
	 *
	 * @param WC_Order $order Order object.
	 * @param bool $force_one_line_item Whether one line item was forced by validation or URL length.
	 *
	 * @return array
	 */
	protected function get_shipping_cost_line_item( $order, $force_one_line_item ) {
		$line_item_args = array();
		$shipping_total = $order->get_shipping_total();
		if ( $force_one_line_item ) {
			$shipping_total += $order->get_shipping_tax();
		}

		// Add shipping costs
		if ( $order->get_shipping_total() > 0 && $order->get_shipping_total() < 999.99 && wgc_number_format( $order->get_shipping_total() + $order->get_shipping_tax() ) !== wgc_number_format( $order->get_total() ) ) {
			$line_item_args['shipping'] = wgc_number_format( $shipping_total );
		} elseif ( $order->get_shipping_total() > 0 ) {
			/* translators: %s: order shipping method */
			$this->add_line_item( sprintf( __( 'Shipping via %s', 'elavon-converge-gateway' ), $order->get_shipping_method() ), 1, wgc_number_format( $shipping_total ) );
		}

		return $line_item_args;
	}

	/**
	 * Get line item args for converge request.
	 *
	 * @param WC_Order $order Order object.
	 * @param bool $force_one_line_item Create only one item for this order.
	 *
	 * @return array
	 */
	public function get_line_item_args( $order, $force_one_line_item = false ) {
		if ( wc_tax_enabled() && wc_prices_include_tax() || ! $this->line_items_valid( $order ) ) {
			$force_one_line_item = true;
		}

		$line_item_args = array();

		/**
		 * Passing a line item per product if supported.
		 */
		$this->prepare_line_items( $order );
		$line_item_args['tax_cart'] = wgc_number_format( $order->get_total_tax() );

		if ( $order->get_total_discount() > 0 ) {
			$line_item_args['discount_amount_cart'] = wgc_number_format( wgc_round( $order->get_total_discount() ) );
		}

		$line_item_args = array_merge( $line_item_args, $this->get_shipping_cost_line_item( $order, false ) );

		return array_merge( $line_item_args, $this->get_line_items() );
	}


	/**
	 * Get order item names as a string.
	 *
	 * @param WC_Order $order Order object.
	 * @param WC_Order_Item $item Order item object.
	 *
	 * @return string
	 */
	protected function get_order_item_name( $order, $item ) {
		$item_name = $item->get_name();
		$item_meta = strip_tags(
			wc_display_item_meta(
				$item, array(
					'before'    => '',
					'separator' => ', ',
					'after'     => '',
					'echo'      => false,
					'autop'     => false,
				)
			)
		);

		if ( $item_meta ) {
			$item_name .= ' (' . $item_meta . ')';
		}

		return apply_filters( 'woocommerce_converge_get_order_item_name', $item_name, $order, $item );
	}

	/**
	 * Return all line items.
	 */
	protected function get_line_items() {
		return $this->line_items;
	}

	/**
	 * Remove all line items.
	 */
	protected function delete_line_items() {
		$this->line_items = array();
	}

	/**
	 * Check if the order has valid line items to use for converge request.
	 *
	 * The line items are invalid in case of mismatch in totals or if any amount < 0.
	 *
	 * @param WC_Order $order Order to be examined.
	 *
	 * @return bool
	 */
	protected function line_items_valid( $order ) {
		$negative_item_amount = false;
		$calculated_total     = 0;

		// Products.
		foreach ( $order->get_items( array( 'line_item', 'fee' ) ) as $item ) {
			if ( 'fee' === $item['type'] ) {
				$item_line_total  = wgc_number_format( $item['line_total'] );
				$calculated_total += $item_line_total;
			} else {
				$item_line_total  = wgc_number_format( $order->get_item_subtotal( $item, false ) );
				$calculated_total += $item_line_total * $item->get_quantity();
			}

			if ( $item_line_total < 0 ) {
				$negative_item_amount = true;
			}
		}
		$mismatched_totals = wgc_number_format( $calculated_total + $order->get_total_tax() + wgc_round( $order->get_shipping_total() ) - wgc_round( $order->get_total_discount() ) ) !== wgc_number_format( $order->get_total() );

		return ! $negative_item_amount && ! $mismatched_totals;
	}

	/**
	 * Get line items to send to converge.
	 *
	 * @param WC_Order $order Order object.
	 */
	protected function prepare_line_items( $order ) {
		$this->delete_line_items();

		// Products.
		foreach ( $order->get_items( array( 'line_item', 'fee' ) ) as $item ) {
			if ( 'fee' === $item['type'] ) {
				$item_line_total = wgc_number_format( $item['line_total'] );
				$this->add_line_item( $item->get_name(), 1, $item_line_total );
			} else {
				$product         = $item->get_product();
				$sku             = $product ? $product->get_sku() : '';
				$item_line_total = wgc_number_format( $order->get_item_subtotal( $item, false ) );
				$this->add_line_item( $this->get_order_item_name( $order, $item ), $item->get_quantity(), $item_line_total, $sku );
			}
		}
	}

	/**
	 * Add Converge Line Item.
	 *
	 * @param string $item_name Item name.
	 * @param int $quantity Item quantity.
	 * @param float $amount Amount.
	 * @param string $item_number Item number.
	 */
	protected function add_line_item( $item_name, $quantity = 1, $amount = 0.0, $item_number = '' ) {

		$item = apply_filters(
			'woocommerce_converge_line_item', array(
			'item_name'   => html_entity_decode( wc_trim_string( $item_name ? $item_name : __( 'Item', 'elavon-converge-gateway' ), 127 ), ENT_NOQUOTES, 'UTF-8' ),
			'quantity'    => (int) $quantity,
			'amount'      => wc_float_to_string( (float) $amount ),
			'item_number' => $item_number,
		), $item_name, $quantity, $amount, $item_number
		);

		$this->line_items['cart'][] = [
			'item_name'   => $item['item_name'],
			'quantity'    => $item['quantity'],
			'amount'      => $item['amount'],
			'item_number' => $item['item_number'],
		];
	}


	/**
	 * Get Custom Fields that will be sent to Converge
	 *
	 * @return array
	 */
	public function get_custom_fields( WC_Order $order = null ) {
		$custom_fields = array(
			WGC_KEY_VENDOR_ID          => WGC_KEY_VENDOR_ID_VALUE,
			WGC_KEY_VENDOR_APP_NAME    => WGC_KEY_VENDOR_APP_NAME_VALUE,
			WGC_KEY_VENDOR_APP_VERSION => WGC_KEY_VENDOR_APP_VERSION_VALUE,
			WGC_KEY_PHP_VERSION		   => PHP_VERSION,
			WGC_KEY_WC_VERSION		   => WC_VERSION,
		);

		if ( $order ) {
			$order_id              = $order->get_id();
			$unique_transaction_id = get_post_meta( $order_id, '_unique_transaction_id', true );

			if ( ! $unique_transaction_id ) {
				$unique_transaction_id = wgc_generate_unique_transaction_id();
				update_post_meta( $order_id, '_unique_transaction_id', $unique_transaction_id );
			}
			$custom_fields[ WGC_KEY_WOOCOMMERCE_ID ] = $unique_transaction_id;
		}

		return $custom_fields;
	}

	public function get_sale_transaction_state( WC_Order $order ) {
		/** @var \Elavon\Converge2\Response\TransactionResponseInterface $response */
		$response = $this->gateway->get_converge_api()->get_order_transaction( $order );
		return $response->getState();
	}

	public function get_sale_transaction_refunded_amount( WC_Order $order ) {
		/** @var \Elavon\Converge2\Response\TransactionResponseInterface $response */
		$response = $this->gateway->get_converge_api()->get_order_transaction( $order );
		return $response->getTotalRefundedAmount();
	}
}
