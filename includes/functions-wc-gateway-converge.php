<?php

use Elavon\Converge2\DataObject\SubscriptionState;
use Elavon\Converge2\DataObject\TransactionState;
use Elavon\Converge2\Request\Payload\ContactDataBuilder;
use Elavon\Converge2\Request\Payload\OrderItemDataBuilder;
use Elavon\Converge2\Request\Payload\ShopperDataBuilder;
use Elavon\Converge2\Request\Payload\AddressDataBuilder;

/**
 * Functions used by plugins
 */
if ( ! class_exists( 'WC_Gateway_Converge_Dependencies' ) ) {
	require_once 'class-wc-gateway-converge-dependencies.php';
}

/**
 * WC Detection
 */
if ( ! function_exists( 'wgc_is_woocommerce_active' ) ) {
	function wgc_is_woocommerce_active() {
		return WC_Gateway_Converge_Dependencies::woocommerce_active_check();
	}
}

/**
 * Helper function to get payment name.
 */
if ( ! function_exists( 'wgc_get_payment_name' ) ) {
	function wgc_get_payment_name() {
		return WGC_PAYMENT_NAME;
	}
}


/**
 * Helper function to extract billTo or shipTo information from an order.
 */
if ( ! function_exists( 'wgc_get_order_address' ) ) {
	function wgc_get_order_address( $order, $address_type ) {
		/** @var WC_Order $order */

		if ( ! in_array( $address_type, array( 'billing', 'shipping' ) ) ) {
			return null;
		}

		$func_get_first_name = "get_{$address_type}_first_name";
		$func_get_last_name  = "get_{$address_type}_last_name";
		$func_get_company    = "get_{$address_type}_company";
		$func_get_postcode   = "get_{$address_type}_postcode";
		$func_get_country    = "get_{$address_type}_country";
		$func_get_address_1  = "get_{$address_type}_address_1";
		$func_get_address_2  = "get_{$address_type}_address_2";
		$func_get_city       = "get_{$address_type}_city";
		$func_get_state      = "get_{$address_type}_state";
		$func_get_phone      = "get_billing_phone";
		$func_get_email      = "get_billing_email";

		$contact_builder = new ContactDataBuilder();
		$full_name       = sprintf( "%s %s", $order->{$func_get_first_name}(), $order->{$func_get_last_name}() );
		$contact_builder->setFullName( $full_name );
		$company = $order->{$func_get_company}();
		$contact_builder->setCompany( $company );
		$postal_code = wc_format_postcode( $order->{$func_get_postcode}(), $order->{$func_get_country}() );
		$contact_builder->setPostalCode( $postal_code );
		$street1 = $order->{$func_get_address_1}();
		$contact_builder->setStreet1( $street1 );
		$street2 = $order->{$func_get_address_2}();
		$contact_builder->setStreet2( $street2 );
		$city = $order->{$func_get_city}();
		$contact_builder->setCity( $city );
		$region = $order->{$func_get_state}();
		$contact_builder->setRegion( $region );
		$country_code = wgc_convert_countrycode_alpha2_to_alpha3( $order->{$func_get_country}() );
		$contact_builder->setCountryCode( $country_code );
		$primary_phone = $order->{$func_get_phone}();
		$contact_builder->setPrimaryPhone( $primary_phone );
		$contact_builder->setEmail( $order->{$func_get_email}() );
		$address = $contact_builder->getDataAsArrayAssoc();

		return array_filter( $address );
	}
}

/**
 * Helper function to extract shopper data from an order.
 */
if ( ! function_exists( 'wgc_get_shopper_data_from_order' ) ) {
	function wgc_get_shopper_data_from_order( $order ) {
		/** @var WC_Order $order */

		$address_builder = new AddressDataBuilder();
		$address_builder->setStreet1( $order->get_billing_address_1() );
		$address_builder->setStreet2( $order->get_billing_address_2() );
		$address_builder->setCity( $order->get_billing_city() );
		$address_builder->setRegion( $order->get_billing_state() );
		$address_builder->setPostalCode( wc_format_postcode( $order->get_billing_postcode(), $order->get_billing_country() ) );
		$address_builder->setCountryCode( wgc_convert_countrycode_alpha2_to_alpha3( $order->get_billing_country() ) );

		$full_name = sprintf( "%s %s", $order->get_billing_first_name(), $order->get_billing_last_name() );

		$shopper_builder = new ShopperDataBuilder();
		$shopper_builder->setFullName( $full_name );
		$shopper_builder->setCompany( $order->get_billing_company() );
		$shopper_builder->setPrimaryAddress( $address_builder->getData() );
		$shopper_builder->setPrimaryPhone( $order->get_billing_phone() );
		$shopper_builder->setEmail( $order->get_billing_email() );
		$shopper_builder->setCustomFields( wgc_get_gateway()->getConvergeOrderWrapper()->get_custom_fields( $order ) );

		return $shopper_builder->getData();
	}
}

/**
 * Helper function to extract shopper data from an user.
 */
if ( ! function_exists( 'wgc_get_shopper_data_from_user' ) ) {
	function wgc_get_shopper_data_from_user( $user_id ) {
		$address_builder = new AddressDataBuilder();
		$address_builder->setStreet1( get_user_meta( $user_id, 'billing_address_1', true ) );
		$address_builder->setStreet2( get_user_meta( $user_id, 'billing_address_2', true ) );
		$address_builder->setCity( get_user_meta( $user_id, 'billing_city', true ) );
		$address_builder->setRegion( get_user_meta( $user_id, 'billing_state', true ) );
		$address_builder->setPostalCode( wc_format_postcode( get_user_meta( $user_id, 'billing_postcode', true ),
			get_user_meta( $user_id, 'billing_country', true ) ) );
		$address_builder->setCountryCode( wgc_convert_countrycode_alpha2_to_alpha3( get_user_meta( $user_id,
			'billing_country',
			true ) ) );

		$user_info = get_userdata( $user_id );
		$full_name = sprintf( "%s %s", $user_info->first_name, $user_info->last_name );

		$shopper_builder = new ShopperDataBuilder();
		$shopper_builder->setFullName( $full_name );
		$shopper_builder->setCompany( get_user_meta( $user_id, 'billing_company', true ) );
		$shopper_builder->setPrimaryAddress( $address_builder->getData() );
		$shopper_builder->setPrimaryPhone( get_user_meta( $user_id, 'billing_phone', true ) );
		$shopper_builder->setEmail( get_user_meta( $user_id, 'billing_email', true ) );

		return $shopper_builder->getData();
	}
}

/**
 * Helper function to build an order item.
 */
if ( ! function_exists( 'wgc_create_order_item' ) ) {
	function wgc_create_order_item( $item_name, $unit_price, $quantity, $item_type ) {
		$cart_item_total_amount = wgc_number_format( wgc_round( $unit_price * $quantity ) );
		$order_item_builder     = new OrderItemDataBuilder();
		$order_item_builder->setTotalAmountCurrencyCode( $cart_item_total_amount, get_woocommerce_currency() );
		$order_item_builder->setQuantity( $quantity );
		$order_item_builder->setUnitPriceAmountCurrencyCode( $unit_price, get_woocommerce_currency() );
		$order_item_builder->setDescription( $item_name );
		$order_item_builder->setType( $item_type );

		return $order_item_builder->getData();
	}
}

/**
 * Helper function to extract billTo information from an order.
 */
if ( ! function_exists( 'wgc_get_order_bill_to' ) ) {
	function wgc_get_order_bill_to( $order ) {
		return wgc_get_order_address( $order, 'billing' );
	}
}

/**
 * Helper function to extract shipTo information from an order.
 */
if ( ! function_exists( 'wgc_get_order_ship_to' ) ) {
	function wgc_get_order_ship_to( $order ) {
		return wgc_get_order_address( $order, 'shipping' );
	}
}

/**
 * Helper function to convert country codes from alpha2 to alpha 3.
 */
if ( ! function_exists( 'wgc_convert_countrycode_alpha2_to_alpha3' ) ) {
	function wgc_convert_countrycode_alpha2_to_alpha3( $alpha2 ) {
		if ( empty( $alpha2 ) ) {
			return $alpha2;
		}

		// convert ISO 31661 alpha 2 to ISO 31661 alpha 3
		$countryData = ( new League\ISO3166\ISO3166 )->alpha2( $alpha2 );

		return $countryData['alpha3'];
	}
}

if ( ! function_exists( 'wgc_get_order_transaction_state' ) ) {
	/**
	 * Helper function to get a Converge TransactionState object from an order.
	 *
	 * @param WC_Order $order
	 *
	 * @return TransactionState|null
	 */
	function wgc_get_order_transaction_state( WC_Order $order ) {
		return wgc_get_gateway()->getConvergeOrderWrapper()->get_sale_transaction_state( $order );
	}
}

/**
 * Helper function to get the Converge 2 gateway.
 */
if ( ! function_exists( 'wgc_get_gateway' ) ) {
	function wgc_get_gateway() {
		/** @var WC_Gateway_Converge $gateway */
		static $gateway;

		if ( ! isset( $gateway ) ) {
			$payment_gateways = wc()->payment_gateways()->payment_gateways();
			$id               = wgc_get_payment_name();
			if ( isset( $payment_gateways[ $id ] ) ) {
				$gateway = $payment_gateways[ $id ];
			}
		}

		return $gateway;
	}
}

/**
 * Helper function to get plugin settings option.
 */
if ( ! function_exists( 'wgc_get_option' ) ) {
	function wgc_get_option( $key, $empty_value = null ) {
		$gateway = wgc_get_gateway();

		if ( $gateway ) {
			return $gateway->get_option( $key, $empty_value );
		}

		return $empty_value;
	}
}

/**
 * Helper function to log messages.
 */
if ( ! function_exists( 'wgc_log' ) ) {
	function wgc_log( $message, $level = WC_Log_Levels::DEBUG ) {
		static $log_enabled;

		if ( ! isset( $log_enabled ) ) {
			$log_enabled = 'yes' === wgc_get_option( 'debug', 'no' );
		}

		if ( ! $log_enabled ) {
			return;
		}

		$logger = wc_get_logger();
		$logger->log( $level, $message, array( 'source' => wgc_get_payment_name() ) );
	}
}

/**
 * Helper function to get origin URL for HPP LightBox.
 */
if ( ! function_exists( 'wgc_get_origin_url' ) ) {
	function wgc_get_origin_url() {
		$home_url = get_home_url();

		$scheme = parse_url( $home_url, PHP_URL_SCHEME );
		$host = parse_url( $home_url, PHP_URL_HOST );
		$port = parse_url( $home_url, PHP_URL_PORT );

		if ( $port ) {
			$host .= ':' . $port;
		}


		return sprintf( '%s://%s', $scheme, $host );
	}
}

/**
 * Helper function to log messages.
 */
if ( ! function_exists( 'wgc_log_converge_response' ) ) {
	function wgc_log_converge_response( \Elavon\Converge2\Response\ResponseInterface $response, $request_type, WC_Order $order = null ) {
		$log_prefix = sprintf( "[tnx-type %s]", $request_type );
		if ( $order ) {
			$log_prefix = sprintf( "[order-id %s]", $order->get_id() ) . $log_prefix;
		}
		if ( $response->isSuccess() ) {
			wgc_log( sprintf( '%s Received data from Converge: %s',
				$log_prefix, $response->getRawResponseBody()) );
		} else {
			if ( $response->hasRawResponse() ) {
				wgc_log( sprintf( '%s Raw response from Converge: %s',
					$log_prefix, $response->getRawResponseBody() ), WC_Log_Levels::ERROR );
			} else {
				wgc_log( sprintf( '%s Converge was not reached. Error: %s',
					$log_prefix, $response->getRawErrorMessage() ), WC_Log_Levels::ERROR );
			}
		}
	}
}

if ( ! function_exists( 'wgc_log_data_with_intro' ) ) {
	function wgc_log_data_with_intro( $data, $description, WC_Order $order = null, $heading = '' ) {
		if ( ! $heading ) {
			$heading = 'Send data to Converge';
		}
		$line = sprintf( "[%s] %s: ", $description, $heading );
		if ( $order ) {
			$line = sprintf( "[order-id %s]", $order->get_id() ) . $line;
		}
		$masked_data = wgc_data_masking($data);

		if ( is_array( $masked_data ) || is_object( $masked_data ) ) {
			$masked_data = json_encode( $masked_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES );
		}


		$message = $line . $masked_data;
		wgc_log( $message );
	}
}

/**
 * Helper function to get and format order description.
 */
if ( ! function_exists( 'wgc_get_order_description' ) ) {
	function wgc_get_order_description( $merchant_name, $processor_account_id ) {
		return sprintf( 'Purchase from %s - %s', $merchant_name, $processor_account_id );
	}
}

/**
 * Helper function to get and format order error note.
 */
if ( ! function_exists( 'wgc_get_order_error_note' ) ) {
	function wgc_get_order_error_note( $message, \Elavon\Converge2\Response\Response $response ) {
		/* translators: %1$s: already translated text, %2$s: non-translatable error message. */
		return sprintf( __( '%1$s Converge error: %2$s', 'elavon-converge-gateway' ), $message, $response->getShortErrorMessage() );
	}
}

/**
 * Helper function to generate unique transaction id.
 */
if ( ! function_exists( 'wgc_generate_unique_transaction_id' ) ) {
	function wgc_generate_unique_transaction_id() {

		if ( ! function_exists( 'random_int' ) ) {
			function random_int( $min, $max ) {
				if ( ! function_exists( 'mcrypt_create_iv' ) ) {
					trigger_error(
						'mcrypt must be loaded for random_int to work',
						E_USER_WARNING
					);

					return null;
				}

				if ( ! is_int( $min ) || ! is_int( $max ) ) {
					trigger_error( '$min and $max must be integer values', E_USER_NOTICE );
					$min = (int) $min;
					$max = (int) $max;
				}

				if ( $min > $max ) {
					trigger_error( '$max can\'t be lesser than $min', E_USER_WARNING );

					return null;
				}

				$range = $counter = $max - $min;
				$bits  = 1;

				while ( $counter >>= 1 ) {
					++ $bits;
				}

				$bytes   = (int) max( ceil( $bits / 8 ), 1 );
				$bitmask = pow( 2, $bits ) - 1;

				if ( $bitmask >= PHP_INT_MAX ) {
					$bitmask = PHP_INT_MAX;
				}

				do {
					$result = hexdec(
						          bin2hex(
							          mcrypt_create_iv( $bytes, MCRYPT_DEV_URANDOM )
						          )
					          ) & $bitmask;
				} while ( $result > $range );

				return $result + $min;
			}
		}

		return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
			// 32 bits for "time_low"
			random_int( 0, 0xffff ), random_int( 0, 0xffff ),
			// 16 bits for "time_mid"
			random_int( 0, 0xffff ),
			// 16 bits for "time_hi_and_version",
			// four most significant bits holds version number 4
			random_int( 0, 0x0fff ) | 0x4000,
			// 16 bits, 8 bits for "clk_seq_hi_res",
			// 8 bits for "clk_seq_low",
			// two most significant bits holds zero and one for variant DCE1.1
			random_int( 0, 0x3fff ) | 0x8000,
			// 48 bits for "node"
			random_int( 0, 0xffff ), random_int( 0, 0xffff ), random_int( 0, 0xffff )
		);
	}
}

/**
 * Helper function to format the price.
 */
if ( ! function_exists( 'wgc_number_format' ) ) {
	function wgc_number_format( $price, $decimals = 2 ) {
		return number_format( $price, $decimals, '.', '' );
	}
}

/**
 * Helper function to round the price.
 */
if ( ! function_exists( 'wgc_round' ) ) {
	function wgc_round( $price, $precision = 2 ) {
		return round( $price, $precision );
	}
}

/**
 * Helper function to get a template from plugin.
 */
if ( ! function_exists( 'wgc_get_template' ) ) {
	function wgc_get_template( $template_name, $args = array() ) {
		return wc_get_template( $template_name, $args, '', WGC_DIR_PATH . 'templates/' );
	}
}

/**
 * Helper function to get Converge Shopper field name for the WC user metadata.
 */
if ( ! function_exists( 'wgc_get_shopper_id_field_name' ) ) {
	function wgc_get_shopper_id_field_name() {
		return sprintf( WGC_KEY_WC_C2_SHOPPER_ID, get_current_blog_id() );
	}
}

/**
 * Helper function to get Converge Shopper Id from WC user metadata.
 */
if ( ! function_exists( 'wgc_get_c2_shopper_id' ) ) {
	function wgc_get_c2_shopper_id() {
		if ( ! is_user_logged_in() ) {
			return null;
		}

		return get_user_meta( get_current_user_id(), wgc_get_shopper_id_field_name(), true );
	}
}

/**
 * Helper function to verify if the current user has Converge Shopper Id in WC user metadata.
 */
if ( ! function_exists( 'wgc_has_c2_shopper_id' ) ) {
	function wgc_has_c2_shopper_id() {
		$c2_shopper_id = wgc_get_c2_shopper_id();

		return ( ! is_null( $c2_shopper_id ) && ! empty( $c2_shopper_id ) );
	}
}

/**
 * Helper function to add Converge Shopper Id in WC user metadata.
 */
if ( ! function_exists( 'wgc_add_c2_shopper_id' ) ) {
	function wgc_add_c2_shopper_id( $c2_shopper_id ) {
		if ( ! is_user_logged_in() || wgc_has_c2_shopper_id() || empty( $c2_shopper_id ) ) {
			return false;
		}

		add_user_meta(
			get_current_user_id(), wgc_get_shopper_id_field_name(), $c2_shopper_id
		);

		return true;
	}
}

/**
 * Helper function to delete deprecated Converge Shopper Id in WC user metadata.
 */
if ( ! function_exists( 'wgc_delete_deprecated_shopper' ) ) {
	function wgc_delete_deprecated_shopper() {

		$c2_shopper_id = wgc_get_c2_shopper_id();
		$gateway       = wgc_get_gateway();
		$log_handler   = wgc_get_converge_response_log_handler();

		if (
			! is_user_logged_in()
			|| empty( $c2_shopper_id )
		) {
			return false;
		}

		if( ! $gateway->getC2ApiService()->canConnect()) {
			wgc_log('Converge is down.');
			return false;
		}

		wgc_log_data_with_intro( $c2_shopper_id, 'get shopper' );
		$shopper = $gateway->getC2ApiService()->getShopper( $c2_shopper_id );
		wgc_log_converge_response( $shopper, 'get shopper' );

		if ( is_null( $shopper->getId() ) ) {
			delete_user_meta(
				get_current_user_id(), wgc_get_shopper_id_field_name(), $c2_shopper_id
			);

			return true;
		}

		return false;
	}
}

/**
 * Helper function to delete all Saved Cards for the current WC user.
 */
if ( ! function_exists( 'wgc_delete_saved_cards' ) ) {
	function wgc_delete_user_saved_cards() {

		if ( ! is_user_logged_in() ) {
			return false;
		}

		$gateway = wgc_get_gateway();
		$tokens  = WC_Payment_Tokens::get_customer_tokens( get_current_user_id(), $gateway->get_gateway_id() );

		if ( count( $tokens ) ) {
			foreach ( $tokens as $token ) {
				WC_Payment_Tokens::delete( $token->get_id() );
			}

			return true;
		}

		return false;
	}
}

if ( ! function_exists( 'wgc_get_converge_response_log_handler' ) ) {
	function wgc_get_converge_response_log_handler() {
		static $handler;

		if ( ! isset( $handler ) ) {
			$handler = new WC_Gateway_Converge_Response_Log_Handler();
		}

		return $handler;
	}
}

function wgc_subscriptions_active() {
	return wgc_get_option( WGC_KEY_ENABLE_SUBSCRIPTIONS, WGC_KEY_ENABLE_SUBSCRIPTIONS_NO ) == WGC_KEY_ENABLE_SUBSCRIPTIONS_YES;
}

if ( ! function_exists( 'wgc_data_masking' ) ) {
	function wgc_data_masking( $data ) {
		if ( ! is_array( $data ) && ! is_object( $data ) ) {
			return $data;
		}

		$secret_string = '*** secret data ***';

		$data = json_decode( json_encode( $data ), true );
		if ( isset( $data['card'] ) ) {
			$data['card'] = $secret_string;
		}

		return $data;
	}
}

function wgc_get_product_price_html( $product, $price = 0, $quantity = 1 ) {


	if ( is_numeric( $price ) ) {
		$price = $price * $quantity;
	}

	$price_html = $price . '<span class="converge-subscription-details">' . wgc_get_subscription_price_string( $product, $quantity ) . '</span>';

	return apply_filters( 'wgc_get_product_price_html', $price_html, $price, $product, $quantity );
}

function wgc_get_subscription_billing_frequency_string( $product ) {

	$billing_frequency       = $product->get_wgc_plan_billing_frequency();
	$billing_frequency_count = $product->get_wgc_plan_billing_frequency_count();

	if ( 1 == $billing_frequency_count ) {
		return sprintf( __( 'every %1$s', 'elavon-converge-gateway' ), $billing_frequency );
	} else {
		return sprintf( __( 'every %1$s %2$ss', 'elavon-converge-gateway' ),
			$billing_frequency_count,
			$billing_frequency );
	}
}

function wgc_get_subscription_price_string( $product, $quantity = 1, $extra_amount_for_introductory_rate = 0 ) {

	$price_html               = " ";
	$introductory_rate_amount = $product->get_wgc_plan_introductory_rate_amount();
	$rate_billing_periods     = $product->get_wgc_plan_introductory_rate_billing_periods();

	$price_html .= wgc_get_subscription_billing_frequency_string($product);

	if ( $product->has_plan_introductory_rate() && (float) $introductory_rate_amount >= 0 && (float) $rate_billing_periods > 0 ) {
		$payment_text = $rate_billing_periods > 1 ? sprintf( __( '%1$s payments', 'elavon-converge-gateway' ), $rate_billing_periods ) : __( '1 payment', 'elavon-converge-gateway' );
		$price_html   .= sprintf( __( ' for the first %1$s and %2$s %3$s for the following payments', 'elavon-converge-gateway' ),
			$payment_text,
			wc_price( ( $product->get_wgc_plan_price() * $quantity ) + $extra_amount_for_introductory_rate ),
			$price_html
		);
	}

	$plan_billing_ending         = $product->get_wgc_plan_billing_ending();
	$plan_ending_billing_periods = $product->get_wgc_plan_ending_billing_periods();

	if ( "billing_periods" == $plan_billing_ending && $plan_ending_billing_periods > 0 ) {
		$payment_text = $plan_ending_billing_periods > 1 ? sprintf( __( '%1$s payments', 'elavon-converge-gateway' ), $plan_ending_billing_periods ) : __( '1 payment', 'elavon-converge-gateway' );
		$price_html   .= sprintf( __( ' (ending after %1$s)', 'elavon-converge-gateway' ), $payment_text );
	}

	return $price_html;
}

function wgc_calculate_additional_payments_tax( $product ) {
	$product = clone $product;
	$product->set_wgc_plan_price( $product->get_wgc_plan_price() );
	$args      = array(
		'qty'   => 1,
		'price' => $product->get_wgc_plan_price(),
	);
	$price_tax = wc_get_price_including_tax( $product, $args );
	$product->set_wgc_plan_price( $price_tax - $product->get_wgc_plan_price() );

	return $product;
}

function wgc_get_product_price_tax($product, $price){
	$product = clone $product;
	$product->set_price( $price );
	$args      = array(
		'qty'   => 1,
		'price' => $price,
	);

	$price_tax = wc_get_price_including_tax( $product, $args );

	return $price_tax;
}

function wgc_get_product_shipping_tax( $product, $price ) {
	$product = clone $product;
	$product->set_tax_status( 'taxable' );
	$product->set_price( $price );
	$args = array(
		'qty'   => 1,
		'price' => $price,
	);

	$shipping_tax = wc_get_price_including_tax( $product, $args ) - $price;

	return $shipping_tax;
}

function wgc_get_product( $product ) {
	if ( ! is_object( $product ) && ! is_int( $product ) ) {
		return false;
	}

	if ( ! is_object( $product ) ) {
		$product = wc_get_product( $product );
	}

	return $product;
}

function wgc_product_is_subscription( $product ) {
	$product = wgc_get_product( $product );

	return apply_filters( 'wgc_product_is_subscription', $product && $product->is_type( array(WGC_SUBSCRIPTION_NAME, WGC_VARIABLE_SUBSCRIPTION_NAME, WGC_SUBSCRIPTION_VARIATION_NAME) ) );
}

function wgc_get_subscriptions_for_user( $user_id = 0, $args = array() ) {
	$user_id       = empty( $user_id ) ? get_current_user_id() : $user_id;
	$posts         = get_posts(
		array_merge(
			array(
				'post_type'      => WGC_SUBSCRIPTION_POST_TYPE,
				'post_status'    => array_keys( wc_get_order_statuses() ),
				'posts_per_page' => - 1,
				'orderby'        => 'date',
				'order'          => 'DESC',
				'meta_query'     => array(
					array(
						'key'   => '_customer_user',
						'value' => $user_id,
					),
				),
			),
			$args
		)
	);
	$subscriptions = array();
	foreach ( $posts as $post ) {
		$subscriptions[] = wgc_get_subscription_object_by_id( $post->ID );
	}

	return $subscriptions;
}

function wgc_get_subscription_statuses() {
	return array(
		SubscriptionState::ACTIVE    => __( 'Active', 'elavon-converge-gateway' ),
		SubscriptionState::CANCELLED => __( 'Cancelled', 'elavon-converge-gateway' ),
		SubscriptionState::COMPLETED => __( 'Completed', 'elavon-converge-gateway' ),
		SubscriptionState::PAST_DUE  => __( 'Active', 'elavon-converge-gateway' ),
		SubscriptionState::UNKNOWN   => __( 'Unknown', 'elavon-converge-gateway' ),
		SubscriptionState::UNPAID    => __( 'Failed', 'elavon-converge-gateway' ),
	);
}

function wgc_get_subscription_woo_status( $converge_status ) {
	$converge_status = strtolower($converge_status);

	$statuses = array(
		SubscriptionState::ACTIVE    => 'processing',
		SubscriptionState::CANCELLED => 'cancelled',
		SubscriptionState::COMPLETED => 'completed',
		SubscriptionState::PAST_DUE  => 'processing',
		SubscriptionState::UNPAID    => 'failed',
		SubscriptionState::UNKNOWN   => 'failed',
	);

	if ( isset( $statuses[ $converge_status ] ) ) {
		return $statuses[ $converge_status ];
	}

	return null;
}

function wgc_get_subscription_related_orders( $subscription ) {
	global $wpdb;
	if ( ! is_object( $subscription ) ) {
		$subscription = wgc_get_subscription_object_by_id( $subscription );
	}

	$results = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT ID FROM $wpdb->posts AS posts LEFT JOIN $wpdb->postmeta AS postmeta
			ON  posts.ID = postmeta.post_id WHERE posts.post_type = 'shop_order' AND postmeta.meta_key = '_wgc_subscription_id'
			AND postmeta.meta_value = %s",
			$subscription->get_id()
		)
	);
	$orders  = array();
	if ( $subscription->get_parent_id() ) {
		$orders[] = $subscription->get_order( $subscription->get_parent_id() );
	}
	foreach ( $results as $result ) {
		$orders[] = wc_get_order( $result->ID );
	}

	return $orders;
}

function wgc_get_subscription_object_by_id( $id ) {
	return WC()->order_factory->get_order( $id );
}

function wgc_format_datetime($date) {
	if (!$date) {
		return $date;
	}

	return date_i18n(get_option('date_format') . " " . get_option('time_format'), strtotime($date));
}

function wgc_format_subscription_date( $date ) {
	if ($date){
		$a =  date_i18n( get_option( 'date_format' ), strtotime( $date ) );
		return $a;
	} else {
		return __( 'No end date', 'elavon-converge-gateway' );
	}
}

function wgc_format_subscription_state( $subscription_state ) {
	if ( isset( wgc_get_subscription_statuses()[ $subscription_state ] ) ) {
		$subscription_state = wgc_get_subscription_statuses()[ $subscription_state ];
	}

	return $subscription_state;
}

function wgc_get_only_subscription_elements_from_order( WC_Order $order ) {
	$subscription_elements = array();
	foreach ( $order->get_items() as $item ) {
		if ( wgc_product_is_subscription( $item->get_product() ) ) {
			for ( $number = 0; $number < $item->get_quantity(); $number ++ ) {
				$subscription_elements[] = $item->get_product();
			}
		}
	}
	return $subscription_elements;
}

function wgc_get_only_subscription_elements_from_cart( $cart = null ) {
	$subscription_elements = array();

	if ( ! $cart ) {
		$cart = WC()->cart->get_cart();
	}

	foreach ( $cart as $cart_key => $cart_item ) {

		if ( wgc_product_is_subscription( $cart_item['data'] ) ) {
			$subscription_elements[ $cart_key ] = $cart_item;
		}
	}

	return $subscription_elements;
}

function wgc_has_subscription_elements_in_order( WC_Order $order ) {
	$subscription_elements = wgc_get_only_subscription_elements_from_order( $order );

	return ( is_array( $subscription_elements ) && count( $subscription_elements ) > 0 );
}

function wgc_order_from_merchant_view_has_subscription_elements() {
	$order_from_merchant_view = isset( $_GET['pay_for_order'] ) && isset( $_GET['key'] );
	if ( $order_from_merchant_view ) {
		$order_id = wc_get_order_id_by_order_key( wc_clean( $_GET['key'] ) );
		if ( $order = wc_get_order( $order_id ) ) {
			if ( wgc_has_subscription_elements_in_order( $order ) ) {
				return true;
			}
		}
	}

	return false;
}

function wgc_order_id_from_merchant_view_has_subscription_elements( $order_id ) {
	$order_from_merchant_view = isset( $_GET['pay_for_order'] ) && isset( $_GET['key'] );
	if ( $order_from_merchant_view ) {
		if ( $order = wc_get_order( $order_id ) ) {
			if ( wgc_has_subscription_elements_in_order( $order ) ) {
				return true;
			}
		}
	}

	return false;
}

function wgc_has_subscription_elements_in_cart() {
	$subscription_elements = wgc_get_only_subscription_elements_from_cart();

	return ( is_array( $subscription_elements ) && count( $subscription_elements ) > 0 );
}

function wgc_get_recurring_totals_elements() {
	$recurring_totals = array();

	if ( ! wgc_has_subscription_elements_in_cart() ) {
		return $recurring_totals;
	}
	$subscription_elements = wgc_get_only_subscription_elements_from_cart();

	$recurring_totals['subtotal'] = array();
	$recurring_totals['discount'] = array();
	$recurring_totals['shipping'] = array();
	$recurring_totals['taxes']    = array();
	$recurring_totals['total']    = array();
	$cart                         = WC()->cart;

	$discount_total = 0;

	$coupon_type = wgc_get_coupon_type( $cart );
	if ( "recurring" == $coupon_type && $cart->get_cart_discount_total() > 0 ) {
		$discount_total = $cart->get_cart_discount_total();
	}

	foreach ( $subscription_elements as $cart_key => $cart_element ) {

		$price = $cart_element['data']->get_price();

		$quantity       = $cart_element['quantity'];
		$subtotal       = $price * $quantity;
		$total          = $subtotal;
		$shipping_total = 0;

		$recurring_totals['subtotal'][] = sprintf( "%s %s", wc_price( $subtotal ), wgc_get_subscription_price_string( $cart_element['data'], $quantity ) );

		if ( $discount_total > 0 ) {
			$recurring_totals['discount'][] = sprintf( "%s %s", wc_price($discount_total * - 1), wgc_get_subscription_billing_frequency_string( $cart_element['data'] ) );
			$total                          -= $discount_total;
		}
		if ( $cart_element['data']->needs_shipping() ) {
			$shipping_total                 = $cart->get_shipping_total();
			$recurring_totals['shipping'][] = sprintf( "%s %s", $cart->get_cart_shipping_total(), wgc_get_subscription_billing_frequency_string( $cart_element['data'] ) );
			$total                          += $shipping_total;
		}

		$shipping_tax                = WC()->cart->get_shipping_tax();
		$total_additional_tax = 0;
		if ( isset( WC()->cart->wgc_recurring_carts[ $cart_key ] ) ) {
			$cart_tax                    = WC()->cart->wgc_recurring_carts[ $cart_key ]->get_taxes_total();
			if ($cart_tax > 0){
				$product_additional_tax      = wgc_calculate_additional_payments_tax( $cart_element['data']);
				$recurring_totals['taxes'][] = sprintf( "<strong>%s</strong> %s",
					wc_price( $cart_tax ),
					wgc_get_subscription_price_string( $product_additional_tax, $quantity, $shipping_tax ) );
				$total                       += $cart_tax;
				$total_additional_tax        += $product_additional_tax->get_wgc_plan_price() * $quantity + $shipping_tax;
			}
		}

		$recurring_totals['total'][] = sprintf( "<strong>%s</strong> %s", wc_price( $total ), wgc_get_subscription_price_string( $cart_element['data'], $quantity, $shipping_total - $discount_total + $total_additional_tax ) );
	}

	return $recurring_totals;
}

function get_recurring_totals_form($page = "cart"){

	if ("cart" != $page && "checkout" != $page)
		return "";

	$recurring_totals_elements = wgc_get_recurring_totals_elements();

	if ( count( $recurring_totals_elements ) > 0 ) {

		return wc_get_template_html(
			'recurring-totals.php',
			array(
				'recurring_totals_elements' => $recurring_totals_elements
			),
			'',
			WGC_DIR_PATH . 'templates/' . $page .'/'
		);
	}
}


function wgc_is_product_compatible_with_subscription( $product ) {
	$product = wgc_get_product( $product );

	return apply_filters( 'wgc_product_is_compatible_with_subscription', $product && $product->is_type(
			array(
				WGC_SUBSCRIPTION_NAME,
				WGC_VARIABLE_SUBSCRIPTION_NAME,
				WGC_SUBSCRIPTION_VARIATION_NAME,
				'simple',
				'variable',
			)
		)
	);
}

function wgc_conditional_payment_gateways( $available_gateways ) {
	// Not in backend (admin)
	if ( is_admin() ) {
		return $available_gateways;
	}

	$hide_other_methods = false;

	if ( wgc_order_from_merchant_view_has_subscription_elements() ) {
		$hide_other_methods = true;
	} else {
		foreach ( WC()->cart->get_cart() as $cart_key => $cart_item ) {
			if ( wgc_product_is_subscription( $cart_item['data'] ) ) {
				$hide_other_methods = true;
			}
		}
	}

	if ( $hide_other_methods ) {

		foreach ( (array) $available_gateways as $key => $value ) {

			if ( $key != WGC_PAYMENT_NAME ) {
				unset( $available_gateways[ $key ] );
			}
		}
	}

	return $available_gateways;
}

function wgc_create_subscription( $args ) {
	$post_args = array(
		'post_type'   => WGC_SUBSCRIPTION_POST_TYPE,
		'post_author' => 0,
		'post_status' => 'wc-pending',
		'post_parent' => absint( $args['order_id'] ),
	);

	$post_id = wp_insert_post( $post_args );

	if ( is_wp_error( $post_id ) || $post_id === 0 ) {
		return new WP_Error( 'subscription-error', __( 'There was an error creating the subscription.', 'elavon-converge-gateway' ) );
	}
	$subscription = WC()->order_factory->get_order( $post_id );
	$subscription->set_currency( $args['order_currency'] );
	$subscription->set_customer_id( $args['customer_user'] );
	$subscription->save();

	$post_params = array(
		'ID'           => $post_id,
		'post_title'   => sprintf( __( 'Subscription #%1$s for the Order #%2$s ', 'elavon-converge-gateway' ), $subscription->get_id(), $args['order_id'] ),
	);
	wp_update_post($post_params);

	return $subscription;
}


function wgc_assign_billing_and_shipping_to_subscription( $order, $subscription ) {

	$address_fields = array(
		'first_name',
		'last_name',
		'company',
		'address_1',
		'address_2',
		'city',
		'state',
		'postcode',
		'country',
		'email',
		'phone'
	);

	foreach ( array( 'billing', 'shipping' ) as $type ) {

		foreach ( $address_fields as $field_key ) {
			$field_var = sprintf( '%1$s_%2$s', $type, $field_key );
			if ( method_exists( $order, 'get_' . $field_var ) ) {
				$address[ $field_key ] = $order->{'get_' . $field_var}();
			}
		}

		$subscription->set_address( $address, $type );
	}
	$subscription->save();

	return $subscription;
}

function wgc_get_subscriptions_for_order( WC_Order $order ) {

	$subscriptions = array();

	$is_renewal_order = get_post_meta( $order->get_id(), '_renewal_order', true );
	$subscription_id  = get_post_meta( $order->get_id(), '_wgc_subscription_id', true );

	if ( $is_renewal_order && $subscription_id ) {

		$subscriptions[] = wgc_get_subscription_object_by_id( $subscription_id );

		return $subscriptions;
	}

	$params = array(
		'post_type'      => WGC_SUBSCRIPTION_POST_TYPE,
		'posts_per_page' => - 1,
		'post_parent'    => $order->get_id(),
		'post_status'    => 'any',
	);

	$posts = get_posts( $params );

	if ( ! $posts ) {
		return $subscriptions;
	}

	foreach ( $posts as $post ) {
		$subscriptions[] = wgc_get_subscription_object_by_id( $post->ID );
	}

	return $subscriptions;
}

function wgc_get_subscription_start_date( \Elavon\Converge2\DataObject\BillingInterval $billing_interval ) {
	$date = new \DateTime( 'NOW' );
	$date->modify( sprintf( '+%s %s', $billing_interval->getCount(), $billing_interval->getTimeUnit() ) );

	return $date->format( 'Y-m-d' );
}

function wgc_get_subscription_stored_card_id( $converge_subscription ) {
	return substr( $converge_subscription->getStoredCard(),
		strrpos( $converge_subscription->getStoredCard(), '/' ) + 1 );
}

function wgc_get_subscription_used_stored_card( $converge_subscription ) {
	$used_stored_card = null;
	$converge_card_id = wgc_get_subscription_stored_card_id( $converge_subscription );
	$customer_tokens  = WC_Payment_Tokens::get_customer_tokens( get_current_user_id(),
		wgc_get_gateway()->get_gateway_id() );
	foreach ( $customer_tokens as $customer_token ) {
		if ( $customer_token->get_token( wgc_get_payment_name() ) == $converge_card_id ) {
			$used_stored_card = $customer_token;
			break;
		}
	}

	return $used_stored_card;
}

function wgc_format_card_expiration_date( $card_expiry ) {
	if ( strpos( $card_expiry, '/' ) !== false ) {
		$card_expiry_array = explode( "/", $card_expiry );
		$exp_month         = trim( $card_expiry_array[0] );
		$card_expiry_year  = trim( $card_expiry_array[1] );
		$exp_year          = substr( $card_expiry_year, - 2 );
	} else {
		$exp_month = substr( $card_expiry, 0, 2 );
		$exp_year  = substr( $card_expiry, - 2 );
	}

	return array( 'month' => $exp_month, 'year' => $exp_year );
}

function wgc_get_coupon_type( $recurring_cart ) {
	$output = "single";
	foreach ( $recurring_cart->get_coupons() as $code => $coupon ) {
		$converge_coupon_type = get_post_meta( $coupon->get_id(), '_converge_subscription_type', true );
		if ( in_array( $converge_coupon_type, [ 'single', 'recurring'] ) ) {
			$output = $converge_coupon_type;
		}
	}

	return $output;
}

function wgc_force_non_logged_user_wc_session() {
	if ( is_user_logged_in() || is_admin() ) {
		return;
	}
	if ( isset( WC()->session ) ) {
		if ( ! WC()->session->has_session() ) {
			WC()->session->set_customer_session_cookie( true );
		}
	}
}

function wgc_get_order_by_transaction_id( $transaction_id ) {
	global $wpdb;
	return $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts as posts INNER JOIN $wpdb->postmeta as meta ON posts.ID = meta.post_id WHERE post_type = 'shop_order' AND meta.meta_key = '_transaction_id' AND meta.meta_value = %s", $transaction_id ) );
}


function wgc_copy_order_meta( $from, $to ) {
	global $wpdb;
	$query   = $wpdb->prepare(
		"SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id = %d
			AND meta_key NOT LIKE 'wgc_%%' AND meta_key NOT LIKE '%%_date' AND meta_key NOT IN ('_transaction_id', '_order_key')",
		$from->get_id()
	);
	$results = $wpdb->get_results( $query );

	foreach ( $results as $result ) {
		update_post_meta( $to->get_id(), $result->meta_key, maybe_unserialize( $result->meta_value ) );
	}
}

function wgc_create_order_from_subscription( $subscription, $new_order_transaction_id ) {
	global $wpdb;
	try {
		/** @var WC_Converge_Subscription $subscription */
		$items                    = $subscription->get_items( array(
			'line_item',
			'fee',
			'shipping',
			'tax',
			'coupon'
		) );
		$renewal_order            = wc_create_order( array( 'customer_id' => $subscription->get_user_id() ) );
		$plan_id                  = get_post_meta( $subscription->get_id(), 'wgc_plan_id', true );
		$subscription_product_qty = get_post_meta( $subscription->get_id(), 'wgc_subscription_product_qty', true );
		$subscription_product     = null;

		$should_update_totals = false;

		$converge_product_plan = wgc_get_gateway()->get_converge_api()->get_plan( $plan_id );

		if ( ! $converge_product_plan->isSuccess() ) {
			$subscription->add_order_note( sprintf( __( 'Invalid Plan Id: %1$s ', 'elavon-converge-gateway' ), $plan_id ) );

			return false;
		}

		$converge_transaction = wgc_get_gateway()->get_converge_api()->get_transaction( $new_order_transaction_id );

		if ( ! $converge_transaction->isSuccess() ) {
			$subscription->add_order_note( sprintf( __( 'Invalid Transaction Id: %1$s ', 'elavon-converge-gateway' ), $new_order_transaction_id ) );

			return false;
		}

		$subscription_total_amount = (float) $subscription->get_total();
		$transaction_total_amount  = (float) $converge_transaction->getTotal()->getAmount(); // new total
		$plan_total_amount         = (float) $converge_product_plan->getTotal()->getAmount();

		if ( $subscription_total_amount != $transaction_total_amount && $plan_total_amount == $transaction_total_amount ) {
			$should_update_totals = true;
		}

		foreach ( $items as $item_id => $item ) {

			if ( $item->get_type() == "line_item" ) {
				$subscription_product = $item->get_product();
			}

			$item_id = wc_add_order_item(
				$renewal_order->get_id(),
				array(
					'order_item_name' => $item->get_name(),
					'order_item_type' => $item->get_type(),
				)
			);

			$new_item = $renewal_order->get_item( $item_id );
			foreach ( $item->get_meta_data() as $meta_data ) {
				$new_item->update_meta_data( $meta_data->key, $meta_data->value );
			}
			$order_itemmeta = $wpdb->get_results( $wpdb->prepare( "SELECT meta_key, meta_value FROM {$wpdb->prefix}woocommerce_order_itemmeta WHERE order_item_id = %d", $item->get_id() ) );
			foreach ( $order_itemmeta as $meta ) {
				wc_update_order_item_meta( $item_id, $meta->meta_key, $meta->meta_value );
			}
			$renewal_order->add_item( $new_item );
		}

		wgc_copy_order_meta( $subscription, $renewal_order );

		$renewal_order->update_meta_data( '_renewal_order', true );
		$renewal_order->update_meta_data( '_wgc_subscription_id', $subscription->get_id() );
		$renewal_order->set_transaction_id( $new_order_transaction_id );
		$renewal_order->set_date_paid( strtotime( $converge_transaction->getCreatedAt() ) );
		$renewal_order->update_status( 'processing' );
		$renewal_order->save();

		if ( $should_update_totals ) {

			$renewal_order->set_total( $transaction_total_amount );
			$renewal_order->save();

			$price_excluding_tax = wc_get_price_excluding_tax($subscription_product);
			$new_line_item_price = $price_excluding_tax * $subscription_product_qty;

			foreach ( $renewal_order->get_items() as $item_id => $item ) {
				if ( $item->get_type() == "line_item" ) {
					$item->set_subtotal( $price_excluding_tax );
					$item->set_total( $new_line_item_price );
					$item->save();
				}
			}
		}

		return $renewal_order;
	} catch ( Exception $e ) {
		return false;
	}
}