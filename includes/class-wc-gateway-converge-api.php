<?php
/**
 * Class WC_Gateway_Converge_Response_Handler file.
 *
 * @package WooCommerce\Gateways
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elavon\Converge2\DataObject\C2ApiFieldName;
use Elavon\Converge2\DataObject\Resource\StoredCard;
use Elavon\Converge2\DataObject\Resource\StoredCardInterface;
use Elavon\Converge2\DataObject\TransactionType;
use Elavon\Converge2\DataObject\ShopperInteraction;
use Elavon\Converge2\DataObject\FilterOperator;
use Elavon\Converge2\Request\Payload\BillingIntervalDataBuilder;
use Elavon\Converge2\Request\Payload\CardDataBuilder;
use Elavon\Converge2\Request\Payload\ContactDataBuilder;
use Elavon\Converge2\Request\Payload\PlanDataBuilder;
use Elavon\Converge2\Request\Payload\StoredCardDataBuilder;
use Elavon\Converge2\Request\Payload\TotalDataBuilder;
use Elavon\Converge2\Request\Payload\TransactionDataBuilder;
use Elavon\Converge2\Request\Payload\OrderDataBuilder;
use Elavon\Converge2\Request\Payload\PaymentSessionDataBuilder;
use Elavon\Converge2\Request\Payload\SubscriptionDataBuilder;
use Elavon\Converge2\Request\PagedListQuery\TransactionListQueryBuilder;
use Elavon\Converge2\Response\StoredCardResponse;
use Elavon\Converge2\Response\SubscriptionResponse;
use Elavon\Converge2\Response\PlanResponse;
use Elavon\Converge2\Response\TransactionResponse;



/**
 * Handles Responses.
 */
class WC_Gateway_Converge_Api {

	/**
	 * Pointer to gateway making the request.
	 *
	 * @var WC_Gateway_Converge
	 */
	protected $gateway;

	/**
	 * Constructor.
	 *
	 * @param WC_Gateway_Converge $gateway
	 */
	public function __construct( WC_Gateway_Converge $gateway ) {
		$this->gateway = $gateway;
	}

	protected function create_shopper( $shopper_data, $order = null ) {
		wgc_log_data_with_intro( $shopper_data, 'create shopper', $order );
		$response = $this->gateway->getC2ApiService()->createShopper( $shopper_data );
		wgc_log_converge_response( $response, 'create shopper', $order );

		if ( $response->isSuccess() ) {
			return $response->getId();
		} elseif ( $order ) {
				$order->add_order_note(
					wgc_get_order_error_note(
						__(
							'Could not create shopper.',
							'elavon-converge-gateway'
						),
						$response
					)
				);
		}

		return null;
	}

	/**
	 * Create Shopper.
	 *
	 * @param WC_Order $order Order object.
	 *
	 * @return string|null
	 */
	public function create_shopper_using_order_data( WC_Order $order ) {

		/** @var \Elavon\Converge2\Response\ShopperResponse $response */
		$shopper_data = wgc_get_shopper_data_from_order( $order );

		return $this->create_shopper( $shopper_data, $order );
	}

	/**
	 * Create Shopper for user from its account data.
	 *
	 * @return string|null
	 */
	public function create_shopper_using_user_data( $user_id ) {

		/** @var \Elavon\Converge2\Response\ShopperResponse $response */
		$shopper_data = wgc_get_shopper_data_from_user( $user_id );

		return $this->create_shopper( $shopper_data );
	}

	public function update_shopper( WC_Order $order, $shopper_id ) {
		/** @var \Elavon\Converge2\Response\TokenResponse $response */
		$shopper_data = wgc_get_shopper_data_from_order( $order );

		wgc_log_data_with_intro( $shopper_data, 'update shopper', $order );
		$response = $this->gateway->getC2ApiService()->updateShopper( $shopper_id, $shopper_data );
		wgc_log_converge_response( $response, 'update shopper', $order );

		if ( $response->isSuccess() ) {
			return true;
		} else {
			$order->add_order_note(
				wgc_get_order_error_note(
					__( 'Failed shopper update.', 'elavon-converge-gateway' ),
					$response
				)
			);
		}

		return false;
	}

	protected function init_transaction_data_builder( WC_Order $order, $converge_order_id ) {

		$transaction_builder = new TransactionDataBuilder();
		$transaction_builder->setType( TransactionType::SALE );
		$transaction_builder->setTotalAmountCurrencyCode(
			$order->get_total(),
			$order->get_currency()
		);
		$transaction_builder->setDoCapture( $this->gateway->getDoCapture() );

		$transaction_builder->setShopperInteraction( ShopperInteraction::ECOMMERCE );
		$transaction_builder->setShipTo( wgc_get_order_ship_to( $order ) );

		$transaction_builder->setShopperEmailAddress( $order->get_billing_email() );
		$transaction_builder->setDoSendReceipt( (bool) $this->gateway->get_option( WGC_KEY_CONVERGE_EMAIL ) );
		if ( ! empty( $order->get_customer_ip_address() ) ) {
			$transaction_builder->setShopperIpAddress( $order->get_customer_ip_address() );
		}
		$transaction_builder->setShopperReference( $order->get_customer_note() );

		$transaction_builder->setShopperStatementNamePhoneUrl(
			$this->gateway->get_option( WGC_KEY_NAME ),
			$this->gateway->get_option( WGC_KEY_PHONE ),
			$this->gateway->get_option( WGC_KEY_URL )
		);
		$transaction_builder->setDescription(
			wgc_get_order_description(
				$this->gateway->get_option( WGC_KEY_MERCHANT_NAME ),
				$this->gateway->get_option( WGC_KEY_PROCESSOR_ACCOUNT_ID )
			)
		);
		$transaction_builder->setShopperLanguageTag( str_replace( '_', '-', get_locale() ) );
		if ( isset( $_COOKIE['wgc_timezone'] ) ) {
			$transaction_builder->setShopperTimeZone( wp_unslash( $_COOKIE['wgc_timezone'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		}

		$transaction_builder->setCustomFields( $this->gateway->getConvergeOrderWrapper()->get_custom_fields( $order ) );
		$transaction_builder->setCreatedBy( WGC_KEY_VENDOR_CREATED_BY_VALUE );
		$transaction_builder->setOrderReference( (string) $order->get_id() );
		$transaction_builder->setOrder( $converge_order_id );

		return $transaction_builder;
	}

	protected function create_sale_transaction( WC_Order $order, TransactionDataBuilder $transaction_builder ) {
		$data = $transaction_builder->getData();

		$stored_card                 = $data->storedCard;
		$subscriptions               = wgc_get_subscriptions_for_order( $order );
		$subscription_added          = array();
		$subscription_has_error      = false;
		$cancelled_subscription_note = __( 'Cancelled subscription. Reason: failed to create initial order or failed to create other subscriptions for parent order.', 'elavon-converge-gateway' );
		$cancelled_order_note        = __( 'Cancelled order. Reason: failed to create subscriptions for this order.', 'elavon-converge-gateway' );

		if ( count( $subscriptions ) > 0 && isset( $stored_card ) && ! empty( $stored_card ) ) {

			foreach ( (array) $subscriptions as $subscription ) {
				$converge_subscription_response = $this->create_subscription( $subscription, $stored_card );

				if ( ! is_null( $converge_subscription_response ) &&
					$converge_subscription_response instanceof \Elavon\Converge2\Response\SubscriptionResponse &&
					$converge_subscription_response->isSuccess() ) {
					$subscription_added[] = $subscription;
				} else {
					$subscription_has_error = true;
				}
			}
		}

		if ( $subscription_has_error ) {

			foreach ( (array) $subscription_added as $subscription ) {
				$this->cancel_subscription( $subscription );
				$subscription->add_order_note( $cancelled_subscription_note );
			}
			$this->payment_failed( $order );
			$order->add_order_note( $cancelled_order_note );
			return;
		}

		wgc_log_data_with_intro( $data, TransactionType::SALE, $order );

		/** @var \Elavon\Converge2\Response\TransactionResponse $response */
		$response = $this->gateway->getc2ApiService()->createSaleTransaction( $data );

		wgc_log_converge_response( $response, TransactionType::SALE, $order );

		if ( $response->isSuccess() ) {

			$transaction_id = $response->getId();

			$capture = $response->getDoCapture();
			$total   = $response->getTotalAmount() . $response->getTotalCurrencyCode();

			/* translators: %1$s: amount, %2$s: transaction id */
			$order->add_order_note(
				sprintf(
					__( 'Authorized %1$s Transaction id: %2$s', 'elavon-converge-gateway' ),
					esc_html( $total ),
					esc_html( $transaction_id )
				)
			);

			if ( $capture ) {
				/* translators: %1$s: amount, %2$s: transaction id */
				$order->add_order_note(
					sprintf(
						__( 'Captured %1$s Transaction id: %2$s', 'elavon-converge-gateway' ),
						esc_html( $total ),
						esc_html( $transaction_id )
					)
				);
			}

			$this->payment_complete( $order, $transaction_id );

		} else {

			// Check if there is a transaction id nonetheless.
			$transaction_id = $response->getId();
			if ( $transaction_id ) {
				$order->set_transaction_id( $transaction_id );
			}

			$order->add_order_note(
				wgc_get_order_error_note(
					__( 'Invalid transaction.', 'elavon-converge-gateway' ),
					$response
				)
			);

			foreach ( (array) $subscription_added as $subscription ) {
				$this->cancel_subscription( $subscription );
				$subscription->add_order_note( $cancelled_subscription_note );
			}

			$this->payment_failed( $order );
		}
	}

	public function create_sale_transaction_with_payment_session( WC_Order $order, $converge_order_id, $payment_session ) {

		$transaction_builder = $this->init_transaction_data_builder( $order, $converge_order_id );
		$transaction_builder->setPaymentSession( $payment_session->getHref() );
		$subscriptions = wgc_get_subscriptions_for_order( $order );

		if ( count( $subscriptions ) > 0 ) {
			$order->add_order_note( 'Customer attempted to create subscription with Blik', false );
			$this->payment_failed( $order );
			wc_add_notice( 'PLACEHOLDER Error - There was an error. Please contact the merchant.', 'error' );
			wp_safe_redirect( wc_get_checkout_url() );
			exit;
		}

		if ( $payment_session->isSuccess() ) {
			$transaction_builder->setThreeDSecure( $payment_session->getThreeDSecure() );
		} else {
			$order_note = wgc_get_order_error_note(
				__( 'Could not retrieve payment session - transaction not created on Converge.', 'elavon-converge-gateway' ),
				$payment_session
			);
			$order->add_order_note( $order_note );
			$this->payment_failed( $order );
			return null;
		}

		$this->create_sale_transaction( $order, $transaction_builder );
	}

	public function create_sale_transaction_with_hosted_card( WC_Order $order, $converge_order_id, $hosted_card, $payment_session_id ) {

		$transaction_builder = $this->init_transaction_data_builder( $order, $converge_order_id );
		$transaction_builder->setHostedCard( $hosted_card );

		if ( $payment_session_id ) {
			$response = $this->gateway->getC2ApiService()->getPaymentSession( $payment_session_id );
			wgc_log_converge_response( $response, 'get payment session', $order );
			if ( $response->isSuccess() ) {
				$transaction_builder->setThreeDSecure( $response->getThreeDSecure() );
			}
		}

		$this->create_sale_transaction( $order, $transaction_builder );
	}

	public function create_sale_transaction_with_stored_card( WC_Order $order, $converge_order_id, $stored_card ) {
		$transaction_builder = $this->init_transaction_data_builder( $order, $converge_order_id );
		$transaction_builder->setStoredCard( $stored_card );
		// TODO: we have to use telephone order until Converge allows e-commerce.
		$transaction_builder->setShopperInteraction( ShopperInteraction::TELEPHONE_ORDER );
		$this->create_sale_transaction( $order, $transaction_builder );
	}

	public function create_order( WC_Order $order ) {

		$ship_to = $this->gateway->getConvergeOrderWrapper()->get_ship_to( $order );
		$items   = $this->gateway->getConvergeOrderWrapper()->get_order_items( $order );

		$order_builder = new OrderDataBuilder();
		$order_builder->setTotalAmountCurrencyCode( $order->get_total(), $order->get_currency() );
		$order_builder->setDescription(
			wgc_get_order_description(
				$this->gateway->get_option( WGC_KEY_MERCHANT_NAME ),
				$this->gateway->get_option( WGC_KEY_PROCESSOR_ACCOUNT_ID )
			)
		);
		$order_builder->setItems( $items );
		$order_builder->setShipTo( $ship_to );
		$order_builder->setShopperEmailAddress( $order->get_billing_email() );
		$order_builder->setShopperReference( $order->get_customer_note() );
		$order_builder->setCustomFields( $this->gateway->getConvergeOrderWrapper()->get_custom_fields( $order ) );

		wgc_log_data_with_intro( $order_builder->getData(), 'create order', $order );

		/** @var \Elavon\Converge2\Response\OrderResponse $response */
		$response = $this->gateway->getC2ApiService()->createOrder( $order_builder->getData() );

		wgc_log_converge_response( $response, 'create order', $order );

		if ( $response->isSuccess() ) {
			return $response->getId();
		} else {
			$order->add_order_note(
				wgc_get_order_error_note(
					__( 'Could not create order on Converge.', 'elavon-converge-gateway' ),
					$response
				)
			);
		}

		return null;
	}

	public function create_payment_session( WC_Order $order, $converge_order_id ) {

		if ( $this->gateway->get_option( WGC_KEY_INTEGRATION_OPTION ) === WGC_SETTING_INTEGRATION_HPP_REDIRECT ) {
			$return_url = WC()->api_request_url( 'wc_payment_gateways' );
			$cancel_url = $order->get_cancel_order_url_raw();
			$hppType    = 'fullPageRedirect';
		} else {
			$return_url = null;
			$cancel_url = null;
			$hppType    = 'lightbox';
		}

		$origin_url = wgc_get_origin_url();

		$payment_session_builder = new PaymentSessionDataBuilder();
		$payment_session_builder->setOrder( $converge_order_id );
		$payment_session_builder->setBillTo( $this->gateway->getConvergeOrderWrapper()->get_billing_args( $order ) );
		$payment_session_builder->setReturnUrl( $return_url );
		$payment_session_builder->setCancelUrl( $cancel_url );
		$payment_session_builder->setOriginUrl( $origin_url );
		$payment_session_builder->setDefaultLanguageTag( str_replace( '_', '-', get_locale() ) );
		$payment_session_builder->setCustomFields( $this->gateway->getConvergeOrderWrapper()->get_custom_fields( $order ) );
		$payment_session_builder->setDoCreateTransaction( false );
		$payment_session_builder->setDoThreeDSecure( true );
		$payment_session_builder->setHppType( $hppType );

		$data = $payment_session_builder->getData();
		wgc_log_data_with_intro( $data, 'create payment session', $order );

		/** @var \Elavon\Converge2\Response\PaymentSessionResponse $response */
		$response = $this->gateway->getC2ApiService()->createPaymentSession( $data );

		wgc_log_converge_response( $response, 'create payment session', $order );

		if ( $response->isSuccess() ) {
			return $response->getId();
		} else {
			$order->add_order_note(
				wgc_get_order_error_note(
					__( 'Could not create payment session.', 'elavon-converge-gateway' ),
					$response
				)
			);
		}

		return null;
	}

	public function void_transaction( WC_Order $order ) {

		$transaction_builder = new TransactionDataBuilder();
		$transaction_builder->setType( TransactionType::VOID );
		$transaction_builder->setParentTransaction( $order->get_transaction_id() );
		$transaction_builder->setCustomFields( $this->gateway->getConvergeOrderWrapper()->get_custom_fields( $order ) );
		$transaction_builder->setCreatedBy( WGC_KEY_VENDOR_CREATED_BY_VALUE );

		/** @var \Elavon\Converge2\Response\TransactionResponse $response */
		$response = $this->gateway->getC2ApiService()->createVoidTransaction( $transaction_builder->getData() );

		wgc_log_converge_response( $response, TransactionType::VOID, $order );

		if ( $response->isSuccess() ) {

			$this->payment_cancelled( $order );

			$total = $response->getTotalAmount() . $response->getTotalCurrencyCode();
			/* translators: %1$s: amount, %2$s: transaction id */
			$order->add_order_note(
				sprintf(
					__( 'Voided %1$s Transaction id: %2$s', 'elavon-converge-gateway' ),
					esc_html( $total ),
					esc_html( $order->get_transaction_id() )
				)
			);

		} else {

			$order->add_order_note(
				wgc_get_order_error_note(
					__(
						'There was an error processing the void.',
						'elavon-converge-gateway'
					),
					$response
				)
			);
		}
	}

	public function refund_transaction( WC_Order $order, $amount ) {

		$transaction_state = wgc_get_order_transaction_state( $order );

		if ( ! $transaction_state || ! $transaction_state->isRefundable() ) {
			return new WP_Error(
				'refund-error',
				/* translators: %s: transaction status */
				sprintf(
					__(
						'Cannot refund a transaction that has the %s status.',
						'elavon-converge-gateway'
					),
					esc_html( $transaction_state->getValue() )
				)
			);
		}

		$transaction_builder = new TransactionDataBuilder();
		$transaction_builder->setType( TransactionType::REFUND );
		$transaction_builder->setParentTransaction( $order->get_transaction_id() );
		$transaction_builder->setCustomFields( $this->gateway->getConvergeOrderWrapper()->get_custom_fields( $order ) );
		$transaction_builder->setCreatedBy( WGC_KEY_VENDOR_CREATED_BY_VALUE );
		$transaction_builder->setTotalAmountCurrencyCode( $amount, $order->get_currency() );

		/** @var \Elavon\Converge2\Response\TransactionResponse $response */
		$response = $this->gateway->getC2ApiService()->createRefundTransaction( $transaction_builder->getData() );

		wgc_log_converge_response( $response, TransactionType::REFUND, $order );

		if ( $response->isSuccess() ) {
			/* translators: %1$s: currency code, %2$s: amount */
			$order->add_order_note(
				sprintf(
					__(
						'Order was successfully refunded in the amount of %1$s%2$s.',
						'elavon-converge-gateway'
					),
					esc_html( $order->get_currency() ),
					esc_html( $amount )
				)
			);

			return true;

		} else {

			$order->add_order_note(
				wgc_get_order_error_note(
					__(
						'There was an error processing the refund.',
						'elavon-converge-gateway'
					),
					$response
				)
			);

			return new WP_Error(
				'refund-error',
				/* translators: %s: error message */
				sprintf(
					__(
						'There was an error processing the refund. %s.',
						'elavon-converge-gateway'
					),
					esc_html( $response->getShortErrorMessage() )
				)
			);

		}
	}

	public function capture_transaction( WC_Order $order ) {

		$transaction_state = wgc_get_order_transaction_state( $order );
		if ( ! $transaction_state || ! $transaction_state->isCapturable() ) {
			return;
		}

		$transaction_id = $order->get_transaction_id();

		/** @var \Elavon\Converge2\Response\TransactionResponse $response */
		$response = $this->gateway->getC2ApiService()->captureTransaction( $transaction_id );
		wgc_log_converge_response( $response, 'capture', $order );

		if ( $response->isSuccess() ) {

			$total = $response->getTotalAmount() . $response->getTotalCurrencyCode();
			/* translators: %1$s: amount, %2$s: transaction id */
			$order->add_order_note(
				sprintf(
					__( 'Captured %1$s Transaction id: %2$s', 'elavon-converge-gateway' ),
					esc_html( $total ),
					esc_html( $transaction_id )
				)
			);

		} else {

			$order->add_order_note(
				wgc_get_order_error_note(
					__(
						'There was an error processing the capture.',
						'elavon-converge-gateway'
					),
					$response
				)
			);
		}
	}

	public function get_order_transaction( WC_Order $order, $force_call = false ) {
		static $cache = array();

		$order_id = $order->get_id();

		if ( ! $force_call && isset( $cache[ $order_id ] ) && $cache[ $order_id ]->isSuccess() ) {
			return $cache[ $order_id ];
		}

		wgc_log_data_with_intro( array( 'transaction_id' => $order->get_transaction_id() ), 'get order transaction', $order );

		$response = $this->gateway->getC2ApiService()->getTransaction( $order->get_transaction_id() );
		wgc_log_converge_response( $response, 'get order transaction', $order );

		$cache[ $order_id ] = $response;

		return $response;
	}

	/**
	 * Complete order, add transaction ID and note.
	 *
	 * @param WC_Order $order Order object.
	 * @param string   $transaction_id Transaction ID.
	 */
	protected function payment_complete( $order, $transaction_id = '' ) {
		$order->payment_complete( $transaction_id );
		WC()->cart->empty_cart();
	}

	/**
	 * Hold order and add note.
	 *
	 * @param WC_Order $order Order object.
	 */
	protected function payment_on_hold( $order ) {
		$order->update_status( 'on-hold' );
		WC()->cart->empty_cart();
	}

	/**
	 * Mark order as voided.
	 *
	 * @param WC_Order $order Order object.
	 */
	protected function payment_voided( $order ) {
		$order->update_status( 'refunded' );
	}

	/**
	 * Mark order as failed.
	 *
	 * @param WC_Order $order Order object.
	 */
	protected function payment_failed( $order ) {
		$order->update_status( 'failed' );
	}


	/**
	 * Mark order as cancelled.
	 *
	 * @param WC_Order $order Order object.
	 */
	protected function payment_cancelled( $order ) {
		$order->update_status( 'cancelled' );
	}

	public function get_hosted_card_three_d_secure( WC_Order $order, $hosted_card ) {
		/** @var \Elavon\Converge2\Response\HostedCardResponse $response */
		$response = $this->gateway->getC2ApiService()->getHostedCard( $hosted_card );

		wgc_log_converge_response( $response, 'get hosted card', $order );

		if ( $response->isSuccess() ) {
			$three_d_secure = $response->getThreeDSecureV1();
			if ( $three_d_secure && $three_d_secure->isSupported() ) {
				return $three_d_secure;
			}
		} else {
			$order->add_order_note(
				wgc_get_order_error_note(
					__(
						'Failed hosted card retrieval.',
						'elavon-converge-gateway'
					),
					$response
				)
			);
		}

		return null;
	}

	public function update_payer_authentication_response_in_hosted_card( WC_Order $order, $hosted_card, $pa_res ) {
		/** @var \Elavon\Converge2\Response\HostedCardResponse $response */
		$response = $this->gateway->getC2ApiService()->updatePayerAuthenticationResponseInHostedCard( $hosted_card, $pa_res );

		wgc_log_converge_response( $response, 'update hosted card', $order );

		if ( $response->isSuccess() ) {
			$three_d_secure = $response->getThreeDSecureV1();
			if ( $three_d_secure && $three_d_secure->isSuccessful() ) {
				return true;
			}
		} else {
			$order->add_order_note(
				wgc_get_order_error_note(
					__( 'Failed hosted card update.', 'elavon-converge-gateway' ),
					$response
				)
			);
		}

		return false;
	}

	/**
	 * @param WC_Order    $order
	 * @param $shopper
	 * @param $hosted_card
	 *
	 * @return mixed|StoredCardInterface|null
	 * @throws \Elavon\Converge2\Exception\InvalidBodyException
	 */
	public function create_stored_card( WC_Order $order, $shopper, $hosted_card ) {
		$stored_card_builder = new StoredCardDataBuilder();
		$stored_card_builder->setShopper( $shopper );
		$stored_card_builder->setHostedCard( $hosted_card );
		$stored_card_builder->setCustomFields( $this->gateway->getConvergeOrderWrapper()->get_custom_fields( $order ) );

		$data = $stored_card_builder->getData();
		wgc_log_data_with_intro( $data, 'create stored card', $order );

		/** @var StoredCardResponse $response */
		$response = $this->gateway->getC2ApiService()->createStoredCard( $data );
		wgc_log_converge_response( $response, 'create stored card', $order );

		if ( $response->isSuccess() ) {
			return new StoredCard( $response->getData() );
		} else {
			$this->payment_failed( $order );
			$order_note = wgc_get_order_error_note(
				__(
					'There was an error creating the stored card.',
					'elavon-converge-gateway'
				),
				$response
			);
			$order->add_order_note( $order_note );

			if ( $response->hasFailuresAboutCardAlreadyExists() ) {
				$errorMsg = __( 'The credit card number could not be saved because the stored card already exists.', 'elavon-converge-gateway' );
			} else {
				$errorMsg = __( 'The credit card number could not be saved.', 'elavon-converge-gateway' );
			}
			wc_add_notice( $errorMsg, 'error' );

			$subscriptions = wgc_get_subscriptions_for_order( $order );

			if ( is_array( $subscriptions ) && count( $subscriptions ) ) {

				foreach ( $subscriptions as $subscription ) {
					$subscription->update_status( 'failed' );
					$subscription->add_order_note( $order_note );
					$subscription->save();
				}
			}

			return null;
		}
	}

	/**
	 * @param WC_Order                 $order
	 * @param $shopper
	 * @param $user_id
	 * @param $card_number
	 * @param $exp_month
	 * @param $exp_year
	 * @param $card_verification_number
	 *
	 * @return mixed|StoredCardInterface|null
	 * @throws \Elavon\Converge2\Exception\InvalidBodyException
	 */
	public function create_stored_card_for_shopper(
		$shopper,
		$user_id,
		$card_number,
		$exp_month,
		$exp_year,
		$card_verification_number
	) {
		$user_info = get_userdata( $user_id );
		$full_name = sprintf(
			'%s %s',
			esc_html( $user_info->first_name ),
			esc_html( $user_info->last_name )
		);

		if ( 2 === strlen( $exp_year ) ) {
			$exp_year = DateTime::createFromFormat( 'y', $exp_year )->format( 'Y' );
		}

		$card_builder = new CardDataBuilder();
		$card_builder->setHolderName( $full_name );
		$card_builder->setNumber( $card_number );
		$card_builder->setExpirationMonth( $exp_month );
		$card_builder->setExpirationYear( $exp_year );
		$card_builder->setSecurityCode( $card_verification_number );

		$bill_to = new ContactDataBuilder();
		$bill_to->setFullName( $full_name );
		$bill_to->setStreet1( get_user_meta( $user_id, 'billing_address_1', true ) );
		$bill_to->setStreet2( get_user_meta( $user_id, 'billing_address_2', true ) );
		$bill_to->setCity( get_user_meta( $user_id, 'billing_city', true ) );
		$bill_to->setRegion( get_user_meta( $user_id, 'billing_state', true ) );
		$bill_to->setPostalCode(
			wc_format_postcode(
				get_user_meta( $user_id, 'billing_postcode', true ),
				get_user_meta( $user_id, 'billing_country', true )
			)
		);
		$bill_to->setCountryCode(
			wgc_convert_countrycode_alpha2_to_alpha3(
				get_user_meta(
					$user_id,
					'billing_country',
					true
				)
			)
		);
		$bill_to->setPrimaryPhone( get_user_meta( $user_id, 'billing_phone', true ) );

		// poate pot folosi wgc_get_order_address()
		$card_builder->setBillTo( $bill_to->getData() );
		$stored_card_builder = new StoredCardDataBuilder();
		$stored_card_builder->setShopper( $shopper );
		$stored_card_builder->setCard( $card_builder->getData() );

		$data = $stored_card_builder->getData();
		wgc_log_data_with_intro( $data, 'create stored card for shopper', null, $shopper );

		/** @var StoredCardResponse $response */
		$response = $this->gateway->getC2ApiService()->createStoredCard( $data );
		wgc_log_converge_response( $response, 'create stored card for shopper' );

		if ( $response->isSuccess() ) {
			return new StoredCard( $response->getData() );
		} else {
			$errorMsg = __( 'Some of your payment fields are invalid.', 'elavon-converge-gateway' );
			wc_add_notice( $errorMsg, 'error' );

			return null;
		}
	}

	public function delete_stored_card( $stored_card ) {
		wgc_log_data_with_intro( $stored_card, 'delete stored card' );

		/** @var StoredCardResponse $response */
		$response = $this->gateway->getC2ApiService()->deleteStoredCard( $stored_card );
		wgc_log_converge_response( $response, 'delete stored card' );

		if ( $response->isSuccess() ) {
			return true;
		}

		return false;
	}

	public function update_stored_card_details(
		$stored_card,
		$exp_month,
		$exp_year,
		$card_verification_number
	) {

		if ( 2 === strlen( $exp_year ) ) {
			$exp_year = DateTime::createFromFormat( 'y', $exp_year )->format( 'Y' );
		}

		$card_builder = new CardDataBuilder();
		$card_builder->setExpirationMonth( $exp_month );
		$card_builder->setExpirationYear( $exp_year );
		$card_builder->setSecurityCode( $card_verification_number );

		$stored_card_builder = new StoredCardDataBuilder();
		$stored_card_builder->setCard( $card_builder->getData() );

		$data = $stored_card_builder->getData();
		wgc_log_data_with_intro( $data, 'update stored card details', null, $stored_card );

		/** @var StoredCardResponse $response */
		$response = $this->gateway->getC2ApiService()->updateStoredCard( $stored_card, $data );
		wgc_log_converge_response( $response, 'update stored card details' );

		if ( $response->isSuccess() ) {
			return new StoredCard( $response->getData() );
		} else {
			$errorMsg = __( 'Some of your payment fields are invalid.', 'elavon-converge-gateway' );
			wc_add_notice( $errorMsg, 'error' );

			return null;
		}
	}

	public function create_product_plan( $product_id, $properties ) {

		$plan_builder = new PlanDataBuilder();
		$plan_builder->setName( sprintf( 'Plan #%s', esc_html( $product_id ) ) );

		$total = new TotalDataBuilder();
		$total->setAmount( $properties['wgc_plan_price'] );
		$total->setCurrencyCode( get_woocommerce_currency() );
		$plan_builder->setTotal( $total->getData() );

		if ( 'yes' === $properties['wgc_plan_introductory_rate'] ) {
			$initial_total = new TotalDataBuilder();
			$initial_total->setAmount( $properties['wgc_plan_introductory_rate_amount'] );
			$initial_total->setCurrencyCode( get_woocommerce_currency() );
			$plan_builder->setInitialTotal( $initial_total->getData() );
			$plan_builder->setInitialTotalBillCount( $properties['wgc_plan_introductory_rate_billing_periods'] );
		}

		$billing_interval = new BillingIntervalDataBuilder();
		$billing_interval->setCount( $properties['wgc_plan_billing_frequency_count'] );
		$billing_interval->setTimeUnit( $properties['wgc_plan_billing_frequency'] );
		$plan_builder->setBillingInterval( $billing_interval->getData() );

		if ( 'billing_periods' === $properties['wgc_plan_billing_ending'] ) {
			$plan_builder->setBillCount( $properties['wgc_plan_ending_billing_periods'] );
		}
		$plan_builder->setShopperStatementNamePhoneUrl(
			$this->gateway->get_option( WGC_KEY_NAME ),
			$this->gateway->get_option( WGC_KEY_PHONE ),
			$this->gateway->get_option( WGC_KEY_URL )
		);
		$plan_builder->setIsSubscribable( true );
		$plan_builder->setCustomReference( null );
		$plan_builder->setCustomFields( $this->gateway->getConvergeOrderWrapper()->get_custom_fields() );
		wgc_log_data_with_intro( $plan_builder->getData(), 'create product plan', null, $product_id );

		/** @var \Elavon\Converge2\Response\OrderResponse $response */
		$response = $this->gateway->getC2ApiService()->createPlan( $plan_builder->getData() );

		wgc_log_converge_response( $response, 'create product plan' );

		return $response;
	}

	public function create_subscription_plan( WC_Converge_Subscription $subscription, $subscription_product ) {

		$product_plan_id = $subscription_product->get_wgc_plan_id();

		$converge_product_plan = $this->get_plan( $product_plan_id );
		if ( ! $converge_product_plan->isSuccess() ) {
			return false;
		}

		$plan_builder = new PlanDataBuilder();
		$plan_builder->setName( sprintf( '%s - Subscription #%s', esc_html( $converge_product_plan->getName() ), esc_html( $subscription->get_id() ) ) );

		$shipping_total           = (float) $subscription->get_shipping_total();
		$initial_total_bill_count = $converge_product_plan->getInitialTotalBillCount();
		$total_amount             = $initial_amount = $subscription->get_total();
		$subscription_product_qty = get_post_meta( $subscription->get_id(), 'wgc_subscription_product_qty', true );
		$coupon_type              = get_post_meta( $subscription->get_id(), 'wgc_coupon_type', true );
		$discount_total           = 0;

		if ( 'recurring' === $coupon_type ) {
			$discount_total = (float) $subscription->get_discount_total();
		}

		if ( ! is_null( $initial_total_bill_count ) && 1 === $initial_total_bill_count ) {
			$initial_total_bill_count = null;
			$initial_amount           = null;
			$total_amount             = ( $subscription_product->get_wgc_plan_price() * $subscription_product_qty ) + $shipping_total - $discount_total;
			$total_amount             = wgc_get_product_price_tax( $subscription_product, $total_amount );
		} elseif ( ! is_null( $initial_total_bill_count ) && $initial_total_bill_count > 1 ) {
			--$initial_total_bill_count;
			$initial_amount = ( $subscription_product->get_wgc_plan_introductory_rate_amount() * $subscription_product_qty ) + $shipping_total - $discount_total;
			$plan_price     = $subscription_product->get_wgc_plan_price();
			$total_amount   = ( $plan_price * $subscription_product_qty ) + $shipping_total - $discount_total;

			$initial_amount = wgc_get_product_price_tax( $subscription_product, $initial_amount );
			$total_amount   = wgc_get_product_price_tax( $subscription_product, $total_amount );

			if ( $subscription_product->get_tax_status() === 'shipping' && $subscription_product->needs_shipping() ) {
				$shipping_tax    = wgc_get_product_shipping_tax( $subscription_product, $shipping_total );
				$initial_amount += $shipping_tax;
				$total_amount   += $shipping_tax;
			}
		}

		$total = new TotalDataBuilder();
		$total->setAmount( $total_amount );
		$total->setCurrencyCode( $converge_product_plan->getTotal()->getCurrencyCode() );
		$plan_builder->setTotal( $total->getData() );

		if ( ! is_null( $converge_product_plan->getInitialTotalBillCount() ) && ! is_null( $initial_total_bill_count ) ) {
			$initial_total = new TotalDataBuilder();
			$initial_total->setAmount( $initial_amount );
			$initial_total->setCurrencyCode( $converge_product_plan->getInitialTotal()->getCurrencyCode() );
			$plan_builder->setInitialTotal( $initial_total->getData() );
			$plan_builder->setInitialTotalBillCount( $initial_total_bill_count );
		}

		$billing_interval = new BillingIntervalDataBuilder();
		$billing_interval->setCount( $converge_product_plan->getBillingInterval()->getCount() );
		$billing_interval->setTimeUnit( $converge_product_plan->getBillingInterval()->getTimeUnit() );
		$plan_builder->setBillingInterval( $billing_interval->getData() );

		if ( ! is_null( $converge_product_plan->getBillCount() ) ) {
			$bill_count = (int) $converge_product_plan->getBillCount();

			if ( $bill_count > 0 ) {
				--$bill_count;
			}

			$plan_builder->setBillCount( $bill_count );
		}

		$plan_builder->setShopperStatementNamePhoneUrl(
			$this->gateway->get_option( WGC_KEY_NAME ),
			$this->gateway->get_option( WGC_KEY_PHONE ),
			$this->gateway->get_option( WGC_KEY_URL )
		);
		$plan_builder->setIsSubscribable( true );
		$plan_builder->setCustomReference( null );
		$plan_builder->setCustomFields( $this->gateway->getConvergeOrderWrapper()->get_custom_fields() );

		wgc_log_data_with_intro( $plan_builder->getData(), 'create subscription plan', $subscription, $subscription_product->get_id() );

		/** @var \Elavon\Converge2\Response\OrderResponse $response */
		$response = $this->gateway->getC2ApiService()->createPlan( $plan_builder->getData() );

		wgc_log_converge_response( $response, 'create subscription plan' );

		return $response;
	}

	public function get_plan( $plan_id ) {
		wgc_log_data_with_intro( array( 'id' => $plan_id ), 'get plan' );

		/** @var PlanResponse $response */
		$response = $this->gateway->getC2ApiService()->getPlan( $plan_id );
		wgc_log_converge_response( $response, 'get plan' );

		return $response;
	}

	public function delete_plan( $plan_id ) {
		wgc_log_data_with_intro( $plan_id, 'delete plan' );

		/** @var StoredCardResponse $response */
		$response = $this->gateway->getC2ApiService()->deletePlan( $plan_id );
		wgc_log_converge_response( $response, 'delete plan' );

		return $response;
	}

	public function get_subscription( $subscription ) {
		wgc_log_data_with_intro( $subscription, 'get subscription' );

		/** @var SubscriptionResponse $response */
		$response = $this->gateway->getC2ApiService()->getSubscription( $subscription );
		wgc_log_converge_response( $response, 'get subscription' );

		return $response;
	}

	public function cancel_subscription( $subscription ) {

		$subscription_builder = new SubscriptionDataBuilder();
		$subscription_builder->setCancelAfterBillNumber( 0 );

		$data = $subscription_builder->getData();
		wgc_log_data_with_intro( $data, 'cancel subscription', null, $subscription->get_id() );

		/** @var \Elavon\Converge2\Response\SubscriptionResponse $response */
		$response = $this->gateway->getC2ApiService()->updateSubscription( $subscription->get_transaction_id(), $data );

		wgc_log_converge_response( $response, 'cancel subscription' );

		if ( $response->isSuccess() ) {
			$subscription->update_status( 'cancelled' );
			$subscription->save();
		}

		return $response;
	}

	public function create_subscription( $subscription, $stored_card ) {

		$order                 = $subscription->get_order();
		$plan_id               = get_post_meta( $subscription->get_id(), 'wgc_plan_id', true );
		$converge_product_plan = $this->get_plan( $plan_id );

		if ( ! $converge_product_plan->isSuccess() ) {
			$subscription->add_order_note( sprintf( __( 'Invalid plan id: %1$s ', 'elavon-converge-gateway' ), esc_html( $plan_id ) ) );
			return null;
		}

		$subscription_start_date = wgc_get_subscription_start_date( $converge_product_plan->getBillingInterval() );

		$subscription_builder = new SubscriptionDataBuilder();
		$subscription_builder->setPlan( $plan_id );
		$subscription_builder->setStoredCard( $stored_card );
		$subscription_builder->setFirstBillAt( $subscription_start_date );
		$subscription_builder->setCustomReference( null );
		$subscription_builder->setCustomFields( $this->gateway->getConvergeOrderWrapper()->get_custom_fields() );

		if ( isset( $_COOKIE['wgc_timezone'] ) ) {
			$subscription_builder->setTimeZoneId( wp_unslash( $_COOKIE['wgc_timezone'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		}

		$data = $subscription_builder->getData();
		wgc_log_data_with_intro( $data, 'create subscription', $order );

		/** @var \Elavon\Converge2\Response\SubscriptionResponse $response */
		$response = $this->gateway->getC2ApiService()->createSubscription( $subscription_builder->getData() );

		wgc_log_converge_response( $response, 'create subscription', $order );

		if ( $response->isSuccess() ) {

			$subscription_id = $response->getId();
			$subscription->add_order_note( sprintf( __( 'Subscription id: %1$s ', 'elavon-converge-gateway' ), esc_html( $subscription_id ) ) );
			$subscription->payment_complete( $subscription_id );

		} else {
			$subscription->add_order_note(
				wgc_get_order_error_note(
					__( 'Could not create subscription.', 'elavon-converge-gateway' ),
					$response
				)
			);
			$subscription->update_status( 'failed' );
		}

		return $response;
	}

	public function update_subscription_stored_card( $subscription, $stored_card ) {

		$subscription_builder = new SubscriptionDataBuilder();
		$subscription_builder->setStoredCard( $stored_card );

		$data = $subscription_builder->getData();
		wgc_log_data_with_intro( $data, 'update subscription', null, $subscription->get_id() );

		/** @var \Elavon\Converge2\Response\SubscriptionResponse $response */
		$response = $this->gateway->getC2ApiService()->updateSubscription( $subscription->get_transaction_id(), $data );

		wgc_log_converge_response( $response, 'update subscription' );

		if ( $response->isSuccess() ) {
			return true;
		} else {
			return null;
		}
	}

	public function get_subscription_transactions( WC_Converge_Subscription $subscription ) {

		$transaction_id = $subscription->get_transaction_id();

		if ( empty( $transaction_id ) ) {
			return false;
		}

		$query_builder = new TransactionListQueryBuilder();
		$query_builder->setFilter( C2ApiFieldName::SUBSCRIPTION, FilterOperator::EQ, $transaction_id );

		/** @var \Elavon\Converge2\Response\TransactionPagedListResponse $response */
		$response = $this->gateway->getC2ApiService()->getTransactionList( $query_builder->getQueryString() );

		return $response;
	}

	public function get_transaction( $transaction_id ) {
		wgc_log_data_with_intro( array( 'id' => $transaction_id ), 'get transaction' );

		/** @var TransactionResponse $response */
		$response = $this->gateway->getC2ApiService()->getTransaction( $transaction_id );
		wgc_log_converge_response( $response, 'get transaction' );

		return $response;
	}

	public function get_payment_session( $payment_session_id ) {
		$response = $this->gateway->getC2ApiService()->getPaymentSession( $payment_session_id );
		wgc_log_converge_response( $response, 'get payment session' );
		return $response;
	}
}
