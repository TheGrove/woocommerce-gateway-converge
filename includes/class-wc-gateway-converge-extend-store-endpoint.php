<?php
/**
 * Elavon Converge Extend Store API.
 *
 * A class to extend the store public API with Converge subscription related data
 *
 * @package WC_Gateway_Converge
 */

use Automattic\WooCommerce\StoreApi\Schemas\ExtendSchema;
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
	 * Register subscription recurring totals data into cart endpoint.
	 *
	 * @return array $recurring_totals_elements Registered data or empty array if condition is not satisfied.
	 */
	public static function extend_cart_data() {
		// return early if we don't have any subscription in cart.
		if ( ! wgc_has_subscription_elements_in_cart() ) {
			return array();
		}

		$recurring_totals_elements = wgc_get_recurring_totals_elements();


		return array(
			'totals' => array_map(
				function( $item ) {
					if ( is_array( $item ) ) {
						return array_map( 'strip_tags', $item );
					} else {
						return strip_tags( $item );
					}
				},
				$recurring_totals_elements
			)
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
