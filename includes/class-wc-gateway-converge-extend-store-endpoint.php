<?php
/**
 * Elavon Converge Extend Store API.
 *
 * A class to extend the store public API with Converge subscription related data
 *
 * @package WC_Gateway_Converge
 */

use Automattic\WooCommerce\StoreApi\Schemas\ExtendSchema;
use Automattic\WooCommerce\StoreApi\Schemas\V1\CartItemSchema;
use Automattic\WooCommerce\StoreApi\Schemas\V1\CartSchema;

class WC_Gateway_Converge_Extend_Store_Endpoint {
	/**
	 * Stores Rest Extending instance.
	 *
	 * @var ExtendSchema
	 */
	private static $extend;

	/**
	 * Plugin Identifier, unique to each plugin.
	 *
	 * @var string
	 */
	const IDENTIFIER = 'woocommerce-gateway-converge';

	/**
	 * Bootstraps the class and hooks required data.
	 *
	 * @param ExtendSchema $extend_rest_api An instance of the ExtendSchema class.
	 */
	public static function init( ExtendSchema $extend_rest_api ) {
		self::$extend         = $extend_rest_api;
		self::extend_store();
	}

	/**
	 * Registers the actual data into each endpoint.
	 */
	public static function extend_store() {
		// Register into `cart/items`
		self::$extend->register_endpoint_data(
			array(
				'endpoint'        => CartItemSchema::IDENTIFIER,
				'namespace'       => self::IDENTIFIER,
				'data_callback'   => array( 'WC_Gateway_Converge_Extend_Store_Endpoint', 'extend_cart_item_data' ),
				'schema_callback' => array( 'WC_Gateway_Converge_Extend_Store_Endpoint', 'extend_cart_item_schema' ),
				'schema_type'     => ARRAY_A,
			)
		);

		// Register into `cart`
		self::$extend->register_endpoint_data(
			array(
				'endpoint'        => CartSchema::IDENTIFIER,
				'namespace'       => self::IDENTIFIER,
				'data_callback'   => array( 'WC_Gateway_Converge_Extend_Store_Endpoint', 'extend_cart_data' ),
				'schema_callback' => array( 'WC_Gateway_Converge_Extend_Store_Endpoint', 'extend_cart_schema' ),
				'schema_type'     => ARRAY_N,
			)
		);
	}

	/**
	 * Register subscription product data into cart/items endpoint.
	 *
	 * @param array $cart_item Current cart item data.
	 *
	 * @return array $item_data Registered data or empty array if condition is not satisfied.
	 */
	public static function extend_cart_item_data( $cart_item ) {
		$product = $cart_item['data'];
		$qty     = $cart_item['quantity'] ?? 1;

		$item_data = array(
			'billing_frequency_subtotal' => '',
			'billing_frequency_total'    => ''
		);

		if ( wgc_product_is_subscription( $product ) ) {
			$item_data = array(
				'billing_frequency_subtotal' => strip_tags( wgc_get_subscription_price_string( $product ) ),
				'billing_frequency_total'    => strip_tags( wgc_get_subscription_price_string( $product, $qty ) ),
			);
		}

		return $item_data;
	}

	/**
	 * Register subscription product schema into cart/items endpoint.
	 *
	 * @return array Registered schema.
	 */
	public static function extend_cart_item_schema() {
		return array(
			'billing_frequency_subtotal' => array(
				'description' => __( 'Billing frequency subtotal string for the subscription.', 'woocommerce-subscriptions' ),
				'type'        => array( 'string' ),
				'context'     => array( 'view', 'edit' ),
				'readonly'    => true,
			),
			'billing_frequency_total'    => array(
				'description' => __( 'Billing frequency total string for the subscription.', 'woocommerce-subscriptions' ),
				'type'        => array( 'string' ),
				'context'     => array( 'view', 'edit' ),
				'readonly'    => true,
			),
		);
	}

	/**
	 * Register subscription recurring totals data into cart endpoint.
	 *
	 * @return array $recurring_totals_elements Registered data or empty array if condition is not satisfied.
	 */
	public static function extend_cart_data() {
		// return early if we don't have any subscription in cart.
		if ( ! wgc_has_subscription_elements_in_cart() ) {
			return array();
		}

		$recurring_totals_elements = wgc_get_recurring_totals_for_blocks();

		return array(
			'totals' => $recurring_totals_elements
		);
	}

	/**
	 * Register subscription recurring totals schema into cart endpoint.
	 *
	 * @return array Registered schema.
	 */
	public static function extend_cart_schema() {
		// return early if we don't have any subscription.
		if ( ! wgc_has_subscription_elements_in_cart() ) {
			return array();
		}

		return array(
			'totals'              => array(
				'description' => __( 'Recurring total', 'elavon-converge-gateway' ),
				'type'        => 'object',
				'context'     => array( 'view', 'edit' ),
				'readonly'    => true,
				'properties'  => array(
					'subtotal'                 => array(
						'description' => __( 'Recurring subtotal of subscription items in the cart.', 'elavon-converge-gateway' ),
						'type'        => 'string',
						'context'     => array( 'view', 'edit' ),
						'readonly'    => true,
					),
					'discount'             => array(
						'description' => __( 'Recurring discount on subscription items.', 'elavon-converge-gateway' ),
						'type'        => 'string',
						'context'     => array( 'view', 'edit' ),
						'readonly'    => true,
					),
					'shipping'                  => array(
						'description' => __( 'Recurring shipping charges', 'elavon-converge-gateway' ),
						'type'        => 'string',
						'context'     => array( 'view', 'edit' ),
						'readonly'    => true,
					),
					'taxes'              => array(
						'description' => __( 'Recurring tax on subscription items', 'elavon-converge-gateway' ),
						'type'        => 'string',
						'context'     => array( 'view', 'edit' ),
						'readonly'    => true,
					),
					'total'              => array(
						'description' => __( 'Recurring total of subscription items in the cart.', 'elavon-converge-gateway' ),
						'type'        => 'string',
						'context'     => array( 'view', 'edit' ),
						'readonly'    => true,
					),
				),
			),
		);
	}
}
