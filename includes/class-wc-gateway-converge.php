<?php

use Elavon\Converge2\Client\ClientConfig;
use Elavon\Converge2\Converge2;
use Elavon\Converge2\DataObject\Resource\StoredCardInterface;
use Elavon\Converge2\DataObject\ThreeDSecureV1;
use Elavon\Converge2\Schema\Converge2Schema;

/**
 * WC_Gateway_Converge Class.
 */
class WC_Gateway_Converge extends WC_Payment_Gateway_CC {

	/**
	 * @var WC_Gateway_Converge_Api
	 */
	private $converge_api;

	/**
	 * @var
	 */
	private $converge_order_wrapper;

	/**
	 * @var League\ISO3166\ISO3166
	 */
	private $isoConvertor;

	/**
	 * Encryption class
	 *
	 * @var \Elavon\Converge2\Util\Encryption
	 */
	protected $encryption = false;

	/** @var bool */
	protected $sandboxMode = false;

	/** @var bool */
	protected $doCapture = true;

	/** @var Converge2 */
	protected $c2ApiService;

	public $stored_card_key;
	public $new_card_key;
	public $new_card_value;

	/**
	 * Constructor for the gateway.
	 */
	public function __construct() {
		$this->id           = wgc_get_payment_name();
		$this->method_title = __( WGC_KEY_TITLE_DEFAULT, 'elavon-converge-gateway' );
		/* translators: %1$s: payment gateway title, already translated */
		$this->method_description = sprintf(
			__(
				'Receive credit card payments using %1$s.',
				'elavon-converge-gateway'
			),
			esc_html( $this->method_title )
		) . '</br>' . $this->get_plugin_versions();
		$this->encryption         = new \Elavon\Converge2\Util\Encryption( wp_salt() );
		$this->stored_card_key    = $this->id . '_stored_card';
		$this->new_card_key       = $this->stored_card_key . '_new';
		$this->new_card_value     = $this->id . '_new_card';

		// Load the settings.
		$this->init_form_fields();
		$this->init_settings();
		$this->set_supports();

		$this->title       = $this->get_option( WGC_KEY_TITLE );
		$this->description = __( 'Pay securely using Elavon Converge.', 'elavon-converge-gateway' );

		$this->sandboxMode =
			WGC_SETTING_ENV_SANDBOX === $this->get_option( WGC_KEY_ENVIRONMENT, WGC_SETTING_ENV_PRODUCTION );
		$this->doCapture   =
			WGC_SETTING_PAYMENT_ACTION_CAPTURE === $this->get_option( WGC_KEY_PAYMENT_ACTION, WGC_SETTING_PAYMENT_ACTION_CAPTURE );

		$this->c2ApiService = $this->createC2ApiService(
			array(
				WGC_KEY_PUBLIC_KEY     => $this->get_option( WGC_KEY_PUBLIC_KEY ),
				WGC_KEY_SECRET_KEY     => $this->get_option( WGC_KEY_SECRET_KEY ),
				WGC_KEY_MERCHANT_ALIAS => $this->get_option( WGC_KEY_MERCHANT_ALIAS ),
				WGC_KEY_ENVIRONMENT    => $this->get_option( WGC_KEY_ENVIRONMENT, WGC_SETTING_ENV_PRODUCTION ),
				WGC_KEY_USE_PROXY      => $this->get_option( WGC_KEY_USE_PROXY ),
				WGC_KEY_PROXY_HOST     => $this->get_option( WGC_KEY_PROXY_HOST ),
				WGC_KEY_PROXY_PORT     => $this->get_option( WGC_KEY_PROXY_PORT ),
			)
		);

		$this->isoConvertor = new League\ISO3166\ISO3166();

		if ( $this->sandboxMode ) {
			$this->description .= ' ' . __(
				'SANDBOX ENABLED. You can use sandbox testing accounts only.',
				'elavon-converge-gateway'
			);
			$this->description  = trim( $this->description );
		}

		add_action(
			'woocommerce_update_options_payment_gateways_' . $this->get_gateway_id(),
			array(
				$this,
				'process_admin_options',
			)
		);
		add_action( 'woocommerce_api_wc_payment_gateways', array( $this, 'return_handler' ) );
		add_filter(
			'woocommerce_settings_api_sanitized_fields_' . $this->get_gateway_id(),
			array( $this, 'encrypt_credential_settings' )
		);
		add_action(
			'woocommerce_order_action_wgc_void_transaction',
			array(
				$this,
				'void_handler',
			)
		);

		add_action(
			'woocommerce_order_action_wgc_capture_transaction',
			array(
				$this,
				'capture_handler',
			)
		);

		add_action( 'woocommerce_receipt_' . $this->get_gateway_id(), array( $this, 'checkout_receipt_page' ) );

		add_filter( 'woocommerce_checkout_fields', array( $this, 'override_checkout_fields' ) );
		add_action( 'woocommerce_after_checkout_validation', array( $this, 'validate_checkout' ), 10, 2 );
		add_action( 'woocommerce_checkout_create_order', array( $this, 'validate_order' ), 10, 2 );
		add_filter( 'woocommerce_payment_methods_list_item', array( $this, 'payment_methods_list_item' ), 10, 2 );
		add_filter( 'woocommerce_available_payment_gateways', array( $this, 'available_payment_gateways' ) );
		add_filter( 'woocommerce_payment_methods_list_item', array( $this, 'wgc_account_saved_payment_methods_list_add_edit_action' ), 10, 2 );

		add_action( 'wp_enqueue_scripts', array( $this, 'store_cards_script' ) );
		add_action( 'woocommerce_after_checkout_form', array( $this, 'enqueue_checkout_assets' ) );
		add_action( 'before_woocommerce_pay', array( $this, 'enqueue_checkout_assets' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// Validate the block checkout input.
		add_action( 'woocommerce_store_api_checkout_update_order_from_request', array( $this, 'validate_block_checkout' ), 10, 2 );

		if ( ! $this->is_valid_for_use() ) {
			$this->enabled = WGC_SETTING_ENABLED_NO;
		}

		$this->converge_api = new WC_Gateway_Converge_Api( $this );

		$this->setConvergeOrderWrapper( new WC_Gateway_Converge_Order_Wrapper( $this ) );
	}

	public function enqueue_admin_scripts() {
		$screen    = get_current_screen();
		$screen_id = $screen ? $screen->id : '';

		if ( isset( $_GET['page'] ) && isset( $_GET['tab'] ) && isset( $_GET['section'] ) && $_GET['page'] === 'wc-settings' && $_GET['tab'] === 'checkout' && $_GET['section'] === 'elavon-converge-gateway' ) { // phpcs:ignore WordPress.Security
			wp_enqueue_script( 'woocommerce', plugins_url( 'assets/js/settings_confirmation.js', WGC_MAIN_FILE ), array(), false, true );
			$params = array( 'delete_alert' => __( 'If you change the account, the stored shoppers and cards will be deleted. Are you sure you want to do this?', 'elavon-converge-gateway' ) );
			wp_localize_script( 'woocommerce', 'elavon_converge_gateway', $params );
		}

		if ( WGC_SUBSCRIPTION_POST_TYPE == $screen_id || 'shop_order' == $screen_id || 'woocommerce_page_wc-orders--wgc_subscription' === $screen_id || 'woocommerce_page_wc-orders' === $screen_id ) {
			wp_enqueue_style( 'wgc_admin_subscription', plugins_url( 'assets/css/admin/subscription.css', WGC_MAIN_FILE ) );
		}
	}

	public function set_supports() {
		$this->supports = array(
			'products',
			'refunds',
			'tokenization',
			'add_payment_method',
			'wgc_subscriptions_change_payment_method',
		);
	}

	public function payment_fields() {
		// Verify if the current shopper still exists on C2. If not, we'll delete it from WC (including saved cards).
		if ( wgc_delete_deprecated_shopper() ) {
			wgc_delete_user_saved_cards();
		}

		if ( is_add_payment_method_page() ) {
			parent::payment_fields();
		} else {
			wgc_get_template(
				'checkout/payment-method.php',
				array(
					'description' => $this->get_description(),
					'gateway'     => $this,
					'tokens'      => $this->get_tokens(),
				)
			);
		}
	}

	public function cc_fields() {
		parent::payment_fields();
	}

	public function field_name( $name ) {
		if ( is_add_payment_method_page() || $this->is_subscription_change_method_page() || $this->is_change_card_details_page() ) {
			return ' name="' . esc_attr( $this->id . '-' . $name ) . '" ' . ' required="required"';
		} else {
			return parent::field_name( $name );
		}
	}

	public function store_cards_script() {
		if ( is_add_payment_method_page() || $this->is_subscription_change_method_page() ) {
			wp_register_script(
				'woocommerce_converge_stored_card_service',
				plugins_url( 'assets/js/add_payment_method.js', WGC_MAIN_FILE ),
				array( 'jquery-payment' ),
				false,
				true
			);
			wp_enqueue_script( 'woocommerce_converge_stored_card_service' );
		}
		if ( $this->is_subscription_change_method_page() ) {
			wp_register_script(
				'woocommerce_converge_change_subscription_payment_method',
				plugins_url( 'assets/js/change_payment_method.js', WGC_MAIN_FILE ),
				array(),
				false,
				true
			);
			wp_enqueue_script( 'woocommerce_converge_change_subscription_payment_method' );
		}
	}

	public function enqueue_checkout_assets() {
		if ( is_checkout() ) {
			wp_register_script( 'woocommerce_converge_save_timezone', plugins_url( 'assets/js/save_timezone.js', WGC_MAIN_FILE ) );
			wp_enqueue_script( 'woocommerce_converge_save_timezone' );
			wp_enqueue_style( 'woocommerce_converge_checkout_css', plugins_url( 'assets/css/elavon_convergegateway.css', WGC_MAIN_FILE ) );
		}
	}

	public function add_payment_method() {
		if ( ! wgc_get_gateway()->getC2ApiService()->canConnect() ) {
			$errorMsg = __( 'Payment method cannot be added due to a connection issue.', 'elavon-converge-gateway' );
			wc_add_notice( $errorMsg, 'error' );
			$error = true;
		} elseif ( $this->isSavePaymentMethodsEnabled() && $this->can_store_one_more_card() ) {
			$card_number              = isset( $_POST['elavon-converge-gateway-card-number'] ) ? wc_clean( $_POST['elavon-converge-gateway-card-number'] ) : ''; // phpcs:ignore WordPress.Security
			$card_expiry              = isset( $_POST['elavon-converge-gateway-card-expiry'] ) ? wc_clean( $_POST['elavon-converge-gateway-card-expiry'] ) : ''; // phpcs:ignore WordPress.Security
			$card_verification_number = isset( $_POST['elavon-converge-gateway-card-cvc'] ) ? wc_clean( $_POST['elavon-converge-gateway-card-cvc'] ) : ''; // phpcs:ignore WordPress.Security

			$error   = false;
			$user_id = get_current_user_id();
			if ( ! wgc_has_c2_shopper_id() ) {
				$shopper_id = $this->converge_api->create_shopper_using_user_data( $user_id );

				if ( is_null( $shopper_id ) ) {
					$error = true;
				}

				wgc_add_c2_shopper_id( $shopper_id );
			} else {
				$shopper_id = wgc_get_c2_shopper_id();
			}

			$expiry = wgc_format_card_expiration_date( $card_expiry );

			$stored_card = $this->converge_api->create_stored_card_for_shopper(
				$shopper_id,
				$user_id,
				$card_number,
				$expiry['month'],
				$expiry['year'],
				$card_verification_number
			);
			if ( $stored_card ) {
				$this->save_stored_card_payment_method_page( $stored_card, $user_id );
			} else {
				$error = true;
			}
		}

		if ( $error ) {
			return array(
				'result'   => 'failed',
				'redirect' => wc_get_endpoint_url( 'add-payment-method' ),
			);
		} else {
			return array(
				'result'   => 'success',
				'redirect' => wc_get_account_endpoint_url( 'payment-methods' ),
			);
		}
	}

	/**
	 * Delete a Plan.
	 */
	public function delete_plan( $plan_id ) {
		return $this->converge_api->delete_plan( $plan_id );
	}

	/**
	 * Void the transaction associated with the WC_Order.
	 *
	 * @param WC_Order $order
	 */
	public function void_handler( $order ) {
		$this->converge_api->void_transaction( $order );
	}

	/**
	 * Capture the transaction associated with the WC_Order.
	 *
	 * @param WC_Order $order
	 */
	public function capture_handler( $order ) {
		$this->converge_api->capture_transaction( $order );
	}

	/**
	 * @param $public_key
	 * @param $secret_key
	 * @param $merchant_alias
	 * @param $sandbox_mode
	 *
	 * @return Converge2
	 */
	protected function createC2ApiService( $config ) {
		$c2_config = new ClientConfig();
		$c2_config->setPublicKey( $config[ WGC_KEY_PUBLIC_KEY ] );
		$c2_config->setSecretKey( $config[ WGC_KEY_SECRET_KEY ] );
		$c2_config->setMerchantAlias( $config[ WGC_KEY_MERCHANT_ALIAS ] );

		if ( $config[ WGC_KEY_ENVIRONMENT ] != WGC_SETTING_ENV_PRODUCTION ) {
			$c2_config->setSandboxMode();
			$api_url = WGC_API_URL_MAP[ $this->get_option( WGC_KEY_ENVIRONMENT ) ];
			$c2_config->setSandboxBaseUri( $api_url );
		}

		if ( ! empty( $config[ WGC_KEY_USE_PROXY ] ) ) {
			$proxy = trim(
				$config[ WGC_KEY_PROXY_HOST ] . ':' . $config[ WGC_KEY_PROXY_PORT ],
				': '
			);
			$c2_config->setProxy( $proxy );
		}

		if ( defined( 'WGC_TIMEOUT' ) ) {
			$c2_config->setTimeout( WGC_TIMEOUT );
		}

		return new Converge2( $c2_config );
	}


	/**
	 * Override for secret key encription.
	 */
	public function init_settings() {

		parent::init_settings();
		if ( ! empty( $this->settings[ WGC_KEY_SECRET_KEY ] ) ) {
			$this->settings[ WGC_KEY_SECRET_KEY ] = $this->decrypt_credential( $this->settings[ WGC_KEY_SECRET_KEY ] );
		}
	}


	/**
	 * Return handler for Hosted Payments.
	 */
	public function return_handler() {
		$payment_session_id = $this->get_payment_session_id();
		$payment_session    = $this->converge_api->get_payment_session( $payment_session_id );

		@ob_clean();
		header( 'HTTP/1.1 200 OK' );
		$hosted_card_key = 'hostedCard';
		if ( $this->isHppPopup() && null === $payment_session->getBlik() ) {
			$hosted_card_key = 'convergePaymentToken';
		}

		$hosted_card_key = htmlentities( wp_unslash( $_REQUEST[ $hosted_card_key ] ), ENT_QUOTES, 'UTF-8' ); // phpcs:ignore WordPress.Security
		if ( isset( $hosted_card_key ) && null === $payment_session->getBlik() ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$hosted_card = wp_unslash( $hosted_card_key ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$this->set_hosted_card_session( $hosted_card );

			$this->handle_return_from_hpp( $hosted_card );

		} elseif ( isset( $_REQUEST['PaRes'] ) ) { // phpcs:ignore WordPress.Security
			$this->handle_return_from_three_d_secure( $_REQUEST ); // phpcs:ignore WordPress.Security

		} elseif ( $payment_session->getBlik() != null ) {
			$this->handle_return_from_blik( $payment_session );
		} else {
			wp_safe_redirect( wc_get_page_permalink( 'cart' ) );
		}

		exit;
	}

	/**
	 * @param $params
	 */
	protected function handle_return_from_hpp( $hosted_card ) {
		$order_id = $this->get_sale_session_order_id();
		if ( $order_id ) {
			$order = wc_get_order( $order_id );
			// HPP takes care of 3DS
			$three_d_secure = false;
			// $three_d_secure = $this->converge_api->get_hosted_card_three_d_secure( $order, $hosted_card );

			if ( $three_d_secure ) {
				echo $this->get_three_d_secure_redirect_form( $hosted_card, $three_d_secure ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			} else {
				$this->do_sale( $order, $hosted_card );
				$this->clear_sale_session();

				wp_safe_redirect( $this->get_return_url( $order ) );
			}
		} else {
			wp_safe_redirect( wc_get_page_permalink( 'cart' ) );
		}
	}

	/**
	 * @param $params
	 */
	protected function handle_return_from_three_d_secure( $params ) {
		$pa_res      = $params['PaRes'];
		$hosted_card = $this->get_hosted_card_session();
		$order_id    = $this->get_sale_session_order_id();
		if ( $hosted_card && $order_id ) {
			$order                = wc_get_order( $order_id );
			$is_three_d_secure_ok =
				$this->converge_api->update_payer_authentication_response_in_hosted_card(
					$order,
					$hosted_card,
					$pa_res
				);

			if ( $is_three_d_secure_ok ) {
				$this->do_sale( $order, $hosted_card );
				wp_safe_redirect( $this->get_return_url( $order ) );
			} else {
				wc_add_notice( __( 'Payment rejected due to 3D Secure.', 'elavon-converge-gateway' ), 'error' );
				wp_safe_redirect( wc_get_page_permalink( 'cart' ) );
			}
			$this->clear_sale_session();
		} else {
			wp_safe_redirect( wc_get_page_permalink( 'cart' ) );
		}
	}

	public function checkout_receipt_page( $order_id ) {

		$payment_session_id  = $this->get_payment_session_id();
		$public_key          = $this->get_option( WGC_KEY_PUBLIC_KEY );
		$merchant_alias      = $this->get_option( WGC_KEY_MERCHANT_ALIAS );
		$lightbox_script_url = WGC_LIGHTBOX_URL_MAP[ $this->get_option( WGC_KEY_ENVIRONMENT ) ];

		if ( ! $order_id || ! $payment_session_id ) {
			return;
		}

		echo '<form method="POST" action="' . esc_attr( WC()->api_request_url( 'wc_payment_gateways' ) ) . '">';
		echo '<script src="' . esc_attr( $lightbox_script_url ) . '" class="converge-button" data-merchant-alias="' . esc_attr( $merchant_alias ) . '" data-public-key="' . esc_attr( $public_key ) . '" data-session-id="' . esc_attr( $payment_session_id ) . '">'; // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript
		echo '</script>';
		echo '</form>';
	}

	/**
	 * @param $params
	 */
	protected function handle_return_from_blik( $payment_session ) {
		$order_id = $this->get_sale_session_order_id();

		if ( $order_id ) {
			$order = wc_get_order( $order_id );
			$this->do_blik_sale( $order, $payment_session );
			$this->clear_sale_session();

			wp_safe_redirect( $this->get_return_url( $order ) );
		} else {
			wp_safe_redirect( wc_get_page_permalink( 'cart' ) );
		}
	}

	/**
	 * @param $hosted_card
	 * @param ThreeDSecureV1 $three_d_secure
	 *
	 * @return string
	 */
	protected function get_three_d_secure_redirect_form( $hosted_card, ThreeDSecureV1 $three_d_secure ) {

		$template_path = WGC_DIR_PATH . 'templates/';

		return wc_get_template_html(
			'three-d-secure-redirect-form.php',
			array(
				'accessControlServerUrl'     => $three_d_secure->getAccessControlServerUrl(),
				'payerAuthenticationRequest' => $three_d_secure->getPayerAuthenticationRequest(),
				'termUrl'                    => WC()->api_request_url( 'wc_payment_gateways' ),
			),
			'',
			$template_path
		);
	}

	protected function do_sale( WC_Order $order, $hosted_card ) {
		$converge_order_id = $this->get_sale_session_converge_order_id();
		$shopper           = wgc_get_c2_shopper_id();
		if (
			$this->isSavePaymentMethodsEnabled()
			&& $this->get_save_for_later_use_session()
			&& $this->can_store_one_more_card()
		) {
			$stored_card = $this->converge_api->create_stored_card( $order, $shopper, $hosted_card );
			if ( $stored_card ) {
				$this->save_stored_card( $order, $stored_card );

				$this->converge_api->create_sale_transaction_with_stored_card(
					$order,
					$converge_order_id,
					$stored_card->getId()
				);
			}
		} else {
			$this->converge_api->create_sale_transaction_with_hosted_card(
				$order,
				$converge_order_id,
				$hosted_card,
				$this->get_payment_session_id()
			);
		}
	}

	protected function do_blik_sale( WC_Order $order, $payment_session ) {
		$converge_order_id = $this->get_sale_session_converge_order_id();

		$this->converge_api->create_sale_transaction_with_payment_session(
			$order,
			$converge_order_id,
			$payment_session
		);
	}

	protected function save_stored_card( WC_Order $order, StoredCardInterface $stored_card ) {
		$token = new WC_Payment_Token_Gateway_Converge_StoredCard();
		$token->init_from_stored_card( $stored_card );
		$token->set_gateway_id( $this->get_gateway_id() );
		$token->set_user_id( $order->get_customer_id() );
		$token->save();
		WC_Payment_Tokens::set_users_default( $order->get_customer_id(), $token->get_id() );
	}

	protected function save_stored_card_payment_method_page( StoredCardInterface $stored_card, $user_id ) {
		$token = new WC_Payment_Token_Gateway_Converge_StoredCard();
		$token->init_from_stored_card( $stored_card );
		$token->set_gateway_id( $this->get_gateway_id() );
		$token->set_user_id( $user_id );
		$token->save();
		WC_Payment_Tokens::set_users_default( $user_id, $token->get_id() );
	}

	/**
	 * Process the payment and return the result.
	 *
	 * @param int $order_id Order ID.
	 *
	 * @return array
	 */
	public function process_payment( $order_id ) {

		$order = wc_get_order( $order_id );
		do_action( 'wgc_checkout_process_payment', $order );

		wgc_log( sprintf( '[order-id %s][customer user agent] %s', esc_html( $order_id ), esc_html( $order->get_customer_user_agent() ) ) );

		// create order on Converge
		$converge_order_id = $this->converge_api->create_order( $order );

		if ( is_null( $converge_order_id ) ) {
			return $this->error_processing_payment();
		}

		if ( is_user_logged_in() && $this->isSavePaymentMethodsEnabled() ) {
			$stored_card_key = htmlentities( wp_unslash( $_POST[ $this->stored_card_key ] ), ENT_QUOTES, 'UTF-8' ); // phpcs:ignore WordPress.Security
			if ( ! isset( $stored_card_key ) || ( isset( $stored_card_key ) && $stored_card_key === $this->new_card_value ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
				if ( isset( $_POST[ WGC_KEY_SAVE_FOR_LATER_USE ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing

					if ( ! wgc_has_c2_shopper_id() ) {
						// add new shopper
						$shopper_id = $this->converge_api->create_shopper_using_order_data( $order );

						if ( is_null( $shopper_id ) ) {
							return $this->error_processing_payment();
						}

						wgc_add_c2_shopper_id( $shopper_id );
					} else {
						// update shopper profile on C2
						$shopper_id      = wgc_get_c2_shopper_id();
						$shopper_updated = $this->converge_api->update_shopper( $order, $shopper_id );

						if ( ! $shopper_updated ) {
							return $this->error_processing_payment();
						}
					}

					// save in session
					$this->set_save_for_later_use_session( wp_unslash( $_POST[ WGC_KEY_SAVE_FOR_LATER_USE ] ) ); // phpcs:ignore WordPress.Security
				}
			} else {
				$payment_token = WC_Payment_Tokens::get( wp_unslash( $stored_card_key ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing WordPress.Security.ValidatedSanitizedInput

				if ( ! $payment_token || $payment_token->get_user_id() !== get_current_user_id() ) {
					return $this->error_processing_payment();
				}

				$stored_card = $payment_token->get_token( wgc_get_payment_name() );

				$this->converge_api->create_sale_transaction_with_stored_card( $order, $converge_order_id, $stored_card );

				return array(
					'result'   => 'success',
					'redirect' => $this->get_return_url( $order ),
				);
			}
		}

		// create payment session on Converge
		$payment_session_id = $this->converge_api->create_payment_session( $order, $converge_order_id );
		if ( is_null( $payment_session_id ) ) {
			return $this->error_processing_payment();
		}

		// save in session
		$this->set_payment_session_id( $payment_session_id );
		$this->set_sale_session( $order_id, $converge_order_id );

		if ( $this->isHppPopup() ) {
			$redirect = $order->get_checkout_payment_url( true );
		} else {
			$hpp_url  = WGC_HPP_URL_MAP[ $this->get_option( WGC_KEY_ENVIRONMENT ) ];
			$redirect = $this->getConvergeOrderWrapper()->get_request_url( $payment_session_id, $hpp_url );
		}

		return array(
			'result'   => 'success',
			'redirect' => $redirect,
		);
	}

	protected function error_processing_payment() {
		wc_add_notice( __( 'There was an error processing your payment.', 'elavon-converge-gateway' ), 'error' );

		return array( 'result' => 'failure' );
	}


	/**
	 * @param int    $order_id
	 * @param null   $amount
	 * @param string $reason
	 *
	 * @return bool|WP_Error
	 */
	public function process_refund( $order_id, $amount = null, $reason = '' ) {
		$order          = wc_get_order( $order_id );
		$transaction_id = $order->get_transaction_id();

		if ( empty( $transaction_id ) ) {
			return new WP_Error(
				'refund-error',
				/* translators: %s: order id */
				sprintf(
					__( 'Order %s does not contain a transaction id.', 'elavon-converge-gateway' ),
					esc_html( $order_id )
				)
			);
		}

		return $this->converge_api->refund_transaction( $order, $amount );
	}


	/**
	 * Initialise Gateway Settings Form Fields.
	 */
	public function init_form_fields() {
		$this->form_fields = include 'settings-converge-payment-gateway.php';
	}


	/**
	 * Check if this gateway is enabled and available in the user's country.
	 *
	 * @return bool
	 */
	public function is_valid_for_use() {
		return true;
	}

	/**
	 * Encrypt multiple credentials.
	 *
	 * @param array $settings gateway settings
	 *
	 * @return array
	 */
	public function encrypt_credential_settings( $settings ) {

		if ( ! empty( $settings[ WGC_KEY_SECRET_KEY ] ) ) {
			$settings[ WGC_KEY_SECRET_KEY ] = $this->encrypt_credential( $settings[ WGC_KEY_SECRET_KEY ] );
		}

		return $settings;
	}

	/**
	 * Encrypt single credential.
	 *
	 * @param string $credential
	 *
	 * @return string
	 */
	public function encrypt_credential( $credential ) {

		if ( empty( $credential ) ) {
			return null;
		}

		return $this->encryption->encryptCredential( $credential );
	}

	/**
	 * Decrypt single credential.
	 *
	 * @param string $credential
	 *
	 * @return string
	 */
	public function decrypt_credential( $credential ) {

		if ( empty( $credential ) ) {
			return null;
		}

		return $this->encryption->decryptCredential( $credential );
	}

	/**
	 * @inheritdoc
	 */
	public function process_admin_options() {
		$this->init_settings();
		$current_settings = $this->settings;

		$post_data   = $this->get_post_data();
		$form_fields = $this->get_form_fields();

		foreach ( $form_fields as $key => $field ) {
			if ( 'title' !== $this->get_field_type( $field ) ) {
				try {
					$this->settings[ $key ] = $this->get_field_value( $key, $field, $post_data );
				} catch ( Exception $e ) {
					$this->add_error( $e->getMessage() );
				}
			}

			if ( ! isset( $current_settings[ $key ] ) ) {
				$current_settings[ $key ] = null;
			}
		}

		$this->settings[ WGC_KEY_TITLE ] = htmlentities( $this->get_field_value( WGC_KEY_TITLE, $form_fields[ WGC_KEY_TITLE ], $post_data ) );

		$config_to_validate = array(
			WGC_KEY_NAME                             => null,
			WGC_KEY_PHONE                            => null,
			WGC_KEY_URL                              => null,
			WGC_KEY_TITLE                            => null,
			WGC_KEY_SAVE_FOR_LATER_USE_MESSAGE       => null,
			WGC_KEY_SUBSCRIPTIONS_DISCLOSURE_MESSAGE => null,
			WGC_KEY_PROCESSOR_ACCOUNT_ID             => null,
		);
		foreach ( array_keys( $config_to_validate ) as $field_name ) {
			$config_to_validate[ $field_name ] = $this->get_field_value( $field_name, $form_fields[ $field_name ], $post_data );
		}

		$validator = new WC_Config_Validator();
		$validator->validate( $config_to_validate );

		foreach ( $validator->getErrorMessages() as $error_message ) {
			$this->add_error( $error_message );
		}

		// Restore saved values
		foreach ( $validator->getViolations() as $violation ) {
			$field_name                    = $violation->getField();
			$this->settings[ $field_name ] = $current_settings[ $field_name ];
		}

		if ( $this->get_field_value( WGC_KEY_ENABLE_SUBSCRIPTIONS, $form_fields[ WGC_KEY_ENABLE_SUBSCRIPTIONS ], $post_data ) === WGC_KEY_ENABLE_SUBSCRIPTIONS_YES ) {
			$this->settings[ WGC_KEY_ENABLE_SAVE_PAYMENT_METHODS ] = WGC_KEY_ENABLE_SAVE_PAYMENT_METHODS_YES;
		}

		// Validate public and secret keys
		$public_key     = $this->get_field_value( WGC_KEY_PUBLIC_KEY, $form_fields[ WGC_KEY_PUBLIC_KEY ], $post_data );
		$secret_key     = $this->get_field_value( WGC_KEY_SECRET_KEY, $form_fields[ WGC_KEY_SECRET_KEY ], $post_data );
		$environment    = $this->get_field_value( WGC_KEY_ENVIRONMENT, $form_fields[ WGC_KEY_ENVIRONMENT ], $post_data );
		$merchant_alias = $this->get_field_value( WGC_KEY_MERCHANT_ALIAS, $form_fields[ WGC_KEY_MERCHANT_ALIAS ], $post_data );
		$use_proxy      = $this->get_field_value( WGC_KEY_USE_PROXY, $form_fields[ WGC_KEY_USE_PROXY ], $post_data );
		$proxy_host     = $this->get_field_value( WGC_KEY_PROXY_HOST, $form_fields[ WGC_KEY_PROXY_HOST ], $post_data );
		$proxy_port     = $this->get_field_value( WGC_KEY_PROXY_PORT, $form_fields[ WGC_KEY_PROXY_PORT ], $post_data );

		$converge2 = $this->createC2ApiService(
			array(
				WGC_KEY_PUBLIC_KEY     => $public_key,
				WGC_KEY_SECRET_KEY     => $secret_key,
				WGC_KEY_MERCHANT_ALIAS => $merchant_alias,
				WGC_KEY_ENVIRONMENT    => $environment,
				WGC_KEY_USE_PROXY      => $use_proxy,
				WGC_KEY_PROXY_HOST     => $proxy_host,
				WGC_KEY_PROXY_PORT     => $proxy_port,
			)
		);

		$log_handler = wgc_get_converge_response_log_handler();

		if ( ! $converge2->canConnect() ) {

			$this->add_error(
				__(
					'The configuration fields could not be validated due to unsuccessful connection to the Converge API.',
					'elavon-converge-gateway'
				)
			);

			foreach (
				array(
					WGC_KEY_PUBLIC_KEY,
					WGC_KEY_SECRET_KEY,
					WGC_KEY_MERCHANT_ALIAS,
					WGC_KEY_PROCESSOR_ACCOUNT_ID,
					WGC_KEY_MERCHANT_NAME,
					WGC_KEY_ENVIRONMENT,
				) as $field_name
			) {
				$this->settings[ $field_name ] = $current_settings[ $field_name ];
			}
		} else {
			$authentication_errors = false;
			if ( ! $converge2->isAuthWithPublicKeyValid() ) {
				$authentication_errors = true;
				$this->add_error( __( 'Invalid public key or merchant alias.', 'elavon-converge-gateway' ) );

				foreach (
					array(
						WGC_KEY_PUBLIC_KEY,
						WGC_KEY_MERCHANT_ALIAS,
						WGC_KEY_ENVIRONMENT,
					) as $field_name
				) {
					$this->settings[ $field_name ] = $current_settings[ $field_name ];
				}
			}

			if ( ! $converge2->isAuthWithSecretKeyValid() ) {
				$authentication_errors = true;
				$this->add_error( __( 'Invalid secret key or merchant alias.', 'elavon-converge-gateway' ) );

				foreach (
					array(
						WGC_KEY_SECRET_KEY,
						WGC_KEY_MERCHANT_ALIAS,
						WGC_KEY_ENVIRONMENT,
					) as $field_name
				) {
					$this->settings[ $field_name ] = $current_settings[ $field_name ];
				}
			}

			// Validate processor account id and set up merchant name
			$processor_account_id = $this->get_field_value( WGC_KEY_PROCESSOR_ACCOUNT_ID, $form_fields[ WGC_KEY_PROCESSOR_ACCOUNT_ID ], $post_data );

			if ( $processor_account_id ) {
				$processor_account_response = $converge2->getProcessorAccount( $processor_account_id );
				if ( ! $processor_account_response->isSuccess() ) {

					foreach (
						array(
							WGC_KEY_PROCESSOR_ACCOUNT_ID,
							WGC_KEY_MERCHANT_NAME,
							WGC_KEY_ENVIRONMENT,
						) as $field_name
					) {
						$this->settings[ $field_name ] = $current_settings[ $field_name ];
					}

					if ( $authentication_errors ) {
						$this->add_error( __( 'The Processor Account Id could not be validated due to unsuccessful connection to the Converge API.', 'elavon-converge-gateway' ) );
					} else {
						$this->add_error( __( 'Invalid processor account id.', 'elavon-converge-gateway' ) );
					}
				} else {
					$merchant_name                           = $processor_account_response->getTradeName();
					$this->settings[ WGC_KEY_MERCHANT_NAME ] = $merchant_name;
				}
			} else {
				$this->settings[ WGC_KEY_MERCHANT_NAME ] = null;
			}
		}

		if ( $this->get_errors() ) {
			$this->display_errors();
		}

		flush_rewrite_rules();

		return update_option(
			$this->get_option_key(),
			apply_filters( 'woocommerce_settings_api_sanitized_fields_' . $this->get_gateway_id(), $this->settings ),
			'yes'
		);
	}

	/**
	 * @param $data
	 * @param $errors
	 */
	public function validate_checkout( $data, $errors ) {
		if ( $data['payment_method'] != wgc_get_payment_name() ) {
			return;
		}
		$this->validate_checkout_input( $data, $errors );
		$this->validate_can_connect_to_converge( $data, $errors );
	}

	public function validate_order( $order, $data ) {
		$this->validate_number_line_items( $order, $data );
	}

	protected function validate_checkout_input( $data, $errors ) {
		$data['billing_full_name']  = $data['billing_first_name'] . ' ' . $data['billing_last_name'];
		$data['shipping_full_name'] = $data['shipping_first_name'] . ' ' . $data['shipping_last_name'];

		$validator = new WC_Checkout_Input_Validator();
		$validator->validate( $data );
		foreach ( $validator->getErrorMessages() as $error_message ) {
			$errors->add( 'validation', $error_message );
		}
	}

	protected function validate_can_connect_to_converge( $data, $errors ) {
		$log_handler = wgc_get_converge_response_log_handler();

		if ( ! $this->getC2ApiService()->canConnect() ) {

			wgc_log( 'Converge is down.' );
			$errors->add(
				'validation',
				__( 'Your order could not be placed. Please try again later.', 'elavon-converge-gateway' )
			);
		}
	}

	protected function validate_number_line_items( $order, $data ) {
		$order_items     = $this->getConvergeOrderWrapper()->get_order_items( $order );
		$order_max_items = Converge2Schema::getInstance()->getOrderMaxItems();

		if ( count( $order_items ) > $order_max_items ) {
			throw new \Exception(
			/* translators: %1$s: number */
				sprintf( esc_html__( 'The number of cart items should be less than or equal to %1$s (including shipping and tax items).', 'elavon-converge-gateway' ), esc_html( $order_max_items ) )
			);
		}
	}

	/**
	 * Validate the block checkout inputs.
	 *
	 * @param WC_Order        $order The order being placed.
	 * @param WP_REST_Request $request The API request currently being processed.
	 *
	 * @return void
	 */
	public function validate_block_checkout( $order, $request ) {
		if ( ! $order instanceof \WC_Order || $request->get_param( 'payment_method' ) !== wgc_get_payment_name() ) {
			return;
		}

		$billing_address  = $request->get_param( 'billing_address' );
		$shipping_address = $request->get_param( 'shipping_address' );

		// Prepare data for validation.
		$data   = array();
		$groups = array( 'billing', 'shipping' );
		$fields = array(
			'first_name',
			'last_name',
			'company',
			'address_1',
			'address_2',
			'city',
			'state',
			'postcode',
			'phone',
		);

		foreach ( $groups as $group ) {
			foreach ( $fields as $field ) {
				$key          = $group . '_' . $field;
				$address      = 'billing' === $group ? $billing_address : $shipping_address;
				$data[ $key ] = isset( $address[ $field ] ) ? wc_clean( wp_unslash( $address[ $field ] ) ) : '';
			}
		}

		$data['order_comments']     = sanitize_textarea_field( $request->get_param( 'customer_note' ) ?? '' );
		$data['billing_full_name']  = $data['billing_first_name'] . ' ' . $data['billing_last_name'];
		$data['shipping_full_name'] = $data['shipping_first_name'] . ' ' . $data['shipping_last_name'];

		$validator = new WC_Checkout_Input_Validator();
		$validator->validate( $data );

		// Add errors to the response.
		$errors = $validator->getErrorMessages();
		foreach ( $errors as $error_message ) {
			wc_add_notice( $error_message, 'error' );
		}

		if ( ! empty( $errors ) ) {
			return;
		}

		// Validate Converge connection.
		if ( ! $this->getC2ApiService()->canConnect() ) {
			wgc_log( 'Converge is down.' );
			wc_add_notice( __( 'Your order could not be placed. Please try again later.', 'elavon-converge-gateway' ), 'error' );
			return;
		}

		// Validate number of line items.
		try {
			$this->validate_number_line_items( $order, null );
		} catch ( \Exception $e ) {
			wc_add_notice( $e->getMessage(), 'error' );
		}
	}

	/**
	 * @param $fields
	 *
	 * @return mixed
	 */
	public function override_checkout_fields( $fields ) {
		$fields['order']['order_comments']['maxlength'] =
			Converge2Schema::getInstance()->getShopperReferenceMaxLength();

		return $fields;
	}

	/**
	 * @return Converge2
	 */
	public function getC2ApiService() {
		return $this->c2ApiService;
	}

	/**
	 * @return bool
	 */
	public function getDoCapture() {
		return $this->doCapture;
	}

	/**
	 * @return WC_Gateway_Converge_Order_Wrapper
	 */
	public function getConvergeOrderWrapper() {
		return $this->converge_order_wrapper;
	}

	/**
	 * @param WC_Gateway_Converge_Order_Wrapper $converge_order_wrapper
	 */
	public function setConvergeOrderWrapper( WC_Gateway_Converge_Order_Wrapper $converge_order_wrapper ) {
		$this->converge_order_wrapper = $converge_order_wrapper;
	}

	protected function set_sale_session( $order_id, $converge_order_id ) {
		WC()->session->set( WGC_KEY_WC_ORDER_ID, $order_id );
		WC()->session->set( WGC_KEY_C2_ORDER_ID, $converge_order_id );
	}

	protected function set_payment_session_id( $payment_session_id ) {
		WC()->session->set( WGC_KEY_C2_PAYMENT_SESSION_ID, $payment_session_id );
	}

	protected function set_hosted_card_session( $hosted_card ) {
		WC()->session->set( WGC_KEY_C2_HOSTED_CARD, $hosted_card );
	}

	protected function set_save_for_later_use_session( $save_for_later_use ) {
		WC()->session->set( WGC_KEY_SAVE_FOR_LATER_USE, $save_for_later_use );
	}

	protected function clear_sale_session() {
		unset( WC()->session->{WGC_KEY_WC_ORDER_ID} );
		unset( WC()->session->{WGC_KEY_C2_ORDER_ID} );
		unset( WC()->session->{WGC_KEY_C2_PAYMENT_SESSION_ID} );
		unset( WC()->session->{WGC_KEY_C2_HOSTED_CARD} );
		unset( WC()->session->{WGC_KEY_SAVE_FOR_LATER_USE} );
	}

	protected function get_sale_session_order_id() {
		return WC()->session->get( WGC_KEY_WC_ORDER_ID );
	}

	protected function get_sale_session_converge_order_id() {
		return WC()->session->get( WGC_KEY_C2_ORDER_ID );
	}

	protected function get_payment_session_id() {
		return WC()->session->get( WGC_KEY_C2_PAYMENT_SESSION_ID );
	}

	protected function get_hosted_card_session() {
		return WC()->session->get( WGC_KEY_C2_HOSTED_CARD );
	}

	protected function get_save_for_later_use_session() {
		return WC()->session->get( WGC_KEY_SAVE_FOR_LATER_USE );
	}

	protected function isHppRedirect() {
		return $this->get_option( WGC_KEY_INTEGRATION_OPTION ) === WGC_SETTING_INTEGRATION_HPP_REDIRECT;
	}

	protected function isHppPopup() {
		return $this->get_option( WGC_KEY_INTEGRATION_OPTION ) === WGC_SETTING_INTEGRATION_HPP_POPUP;
	}

	public function get_converge_api() {
		return $this->converge_api;
	}

	public function isSavePaymentMethodsEnabled() {
		return $this->get_option( WGC_KEY_ENABLE_SAVE_PAYMENT_METHODS ) === WGC_KEY_ENABLE_SAVE_PAYMENT_METHODS_YES;
	}

	public function can_store_one_more_card() {
		return count( $this->get_tokens() ) < WGC_MAX_STORED_CARDS;
	}

	/**
	 * @param $item
	 * @param WC_Payment_Token_Gateway_Converge_StoredCard $payment_token
	 *
	 * @return mixed
	 */
	public function payment_methods_list_item( $item, $payment_token ) {
		if ( WGC_PAYMENT_TOKEN_TYPE !== $payment_token->get_type() ) {
			return $item;
		}
		$item['method']['last4'] = $payment_token->get_last4();
		$item['method']['brand'] = $payment_token->get_card_scheme();
		$item['expires']         = $payment_token->get_expiry_month() . '/' . substr( $payment_token->get_expiry_year(), - 2 );

		return $item;
	}

	public function available_payment_gateways( $gateways ) {
		global $wp;

		if ( is_add_payment_method_page() ) {
			if ( wgc_delete_deprecated_shopper() ) {
				wgc_delete_user_saved_cards();
			}
		}

		if ( is_add_payment_method_page() && isset( $wp->query_vars['add-payment-method'] ) ) {
			if ( ! $this->isSavePaymentMethodsEnabled() ) {
				unset( $gateways[ wgc_get_payment_name() ] );
			}
		}

		return $gateways;
	}

	public function delete_stored_card( $stored_card ) {
		return $this->converge_api->delete_stored_card( $stored_card );
	}

	public function get_gateway_id() {
		return $this->id;
	}

	public function uses_internet_explorer_or_edge() {
		$user_agent = isset( $_SERVER['HTTP_USER_AGENT'] ) ? htmlentities( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ), ENT_QUOTES, 'UTF-8' ) : ''; // phpcs:ignore WordPress.Security

		return preg_match( '~MSIE|Internet Explorer~i', $user_agent ) || ( strpos( $user_agent, 'Trident/7.0' ) !== false && strpos( $user_agent, 'rv:11.0' ) !== false ) || ( preg_match( '/Edge/i', $user_agent ) );
	}

	public function get_plugin_versions() {
		return sprintf( __( 'Plugin version: %1$s', 'elavon-converge-gateway' ), WGC_VERSION ) . ' | ' . sprintf( __( 'API SDK version: %1$s', 'elavon-converge-gateway' ), esc_html( Converge2::getSdkVersion() ) );
	}

	public function change_subscription_payment_method( $subscription ) {
		$stored_card_key = htmlentities( wp_unslash( $_POST[ $this->stored_card_key ] ), ENT_QUOTES, 'UTF-8' ); // phpcs:ignore WordPress.Security
		if ( isset( $stored_card_key ) && $stored_card_key === $this->new_card_value ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
			if ( $this->add_payment_method()['result'] !== 'success' ) {
				return;
			}
			$payment_token = WC_Payment_Tokens::get_customer_default_token( $subscription->get_customer_id() );
		} else {
			$payment_token = WC_Payment_Tokens::get( wp_unslash( $stored_card_key ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
		}
		if ( ! $payment_token || $payment_token->get_user_id() !== get_current_user_id() ) {
			wc_add_notice( __( 'Error updating payment method.', 'elavon-converge-gateway' ), 'error' );
			return;
		}

		$stored_card = $payment_token->get_token( wgc_get_payment_name() );

		if ( ! $this->get_converge_api()->update_subscription_stored_card( $subscription, $stored_card ) ) {
			wc_add_notice( __( 'Error updating payment method.', 'elavon-converge-gateway' ), 'error' );
		}
	}

	public function is_subscription_change_method_page() {
		global $wp;

		return is_account_page() && isset( $wp->query_vars['converge-subscription-change-method'] );
	}

	public function is_change_card_details_page() {
		global $wp;

		return is_account_page() && isset( $wp->query_vars['converge-change-card-details'] );
	}

	public function change_card_details_fields() {
		wp_enqueue_script( 'wc-credit-card-form' );

		$fields = array();

		$cvc_field = '<p class="form-row form-row-last">
			<label for="' . esc_attr( $this->id ) . '-card-cvc">' . esc_html__( 'Card code', 'woocommerce' ) . '&nbsp;<span class="required">*</span></label>
			<input id="' . esc_attr( $this->id ) . '-card-cvc" class="input-text wc-credit-card-form-card-cvc" inputmode="numeric" autocomplete="off" autocorrect="no" autocapitalize="no" spellcheck="no" type="tel" maxlength="4" placeholder="' . esc_attr__( 'CVC', 'woocommerce' ) . '" ' . $this->field_name( 'card-cvc' ) . ' style="width:100px" />
		</p>';

		$default_fields = array(
			'card-expiry-field' => '<p class="form-row form-row-first">
				<label for="' . esc_attr( $this->id ) . '-card-expiry">' . esc_html__( 'Expiry (MM/YY)', 'woocommerce' ) . '&nbsp;<span class="required">*</span></label>
				<input id="' . esc_attr( $this->id ) . '-card-expiry" class="input-text wc-credit-card-form-card-expiry" inputmode="numeric" autocomplete="cc-exp" autocorrect="no" autocapitalize="no" spellcheck="no" type="tel" placeholder="' . esc_attr__( 'MM / YY', 'woocommerce' ) . '" ' . $this->field_name( 'card-expiry' ) . ' />
			</p>',
		);

		if ( ! $this->supports( 'credit_card_form_cvc_on_saved_method' ) ) {
			$default_fields['card-cvc-field'] = $cvc_field;
		}

		$fields = wp_parse_args( $fields, apply_filters( 'woocommerce_credit_card_form_fields', $default_fields, $this->id ) );
		?>

		<fieldset id="wc-<?php echo esc_attr( $this->id ); ?>-cc-form" class='wc-credit-card-form wc-payment-form'>
			<?php do_action( 'woocommerce_credit_card_form_start', $this->id ); ?>
			<?php
			foreach ( $fields as $field ) {
				echo wp_kses_post( $field ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
			}
			?>
			<?php do_action( 'woocommerce_credit_card_form_end', $this->id ); ?>
			<div class="clear"></div>
		</fieldset>
		<?php

		if ( $this->supports( 'credit_card_form_cvc_on_saved_method' ) ) {
			echo '<fieldset>' . wp_kses_post( $cvc_field ) . '</fieldset>'; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
		}
	}

	public function wgc_account_saved_payment_methods_list_add_edit_action( $item, $token ) {
		if ( $token->get_type() === WGC_PAYMENT_TOKEN_TYPE ) {
			$url = wc_get_endpoint_url( 'converge-change-card-details', $token->get_id() );

			$item['actions']['wgc_edit'] = array(
				'url'  => $url,
				'name' => __( 'Edit', 'elavon-converge-gateway' ),
			);
		}

		return $item;
	}
}
