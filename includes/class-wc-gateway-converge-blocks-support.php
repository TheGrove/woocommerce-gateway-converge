<?php
/**
 * Elavon Converge payment method Blocks Support
 *
 * @package WC_Gateway_Converge
 */

use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;

/**
 * Elavon Converge payment method integration
 *
 * @since x.x.x
 */
final class WC_Gateway_Converge_Blocks_Support extends AbstractPaymentMethodType {
	/**
	 * Name of the payment method.
	 *
	 * @var string
	 */
	protected $name = WGC_PAYMENT_NAME;

	/**
	 * Payment Gateway instance.
	 *
	 * @var WC_Gateway_Converge $gateway
	 */
	protected $gateway = null;

	/**
	 * Initializes the payment method type.
	 */
	public function initialize() {
		$this->settings = get_option( 'woocommerce_' . $this->name . '_settings', array() );

		add_action(
			'woocommerce_blocks_enqueue_checkout_block_scripts_before',
			function () {
				add_filter( 'woocommerce_saved_payment_methods_list', array( $this, 'add_saved_payment_methods' ), 10, 2 );
			}
		);
		add_action(
			'woocommerce_blocks_enqueue_checkout_block_scripts_after',
			function () {
				remove_filter( 'woocommerce_saved_payment_methods_list', array( $this, 'add_saved_payment_methods' ), 10, 2 );
			}
		);
	}

	/**
	 * Returns if this payment method should be active. If false, the scripts will not be enqueued.
	 *
	 * @return boolean
	 */
	public function is_active() {
		return $this->get_gateway()->is_available() ?? false;
	}

	/**
	 * Returns an array of scripts/handles to be registered for this payment method.
	 *
	 * @return array
	 */
	public function get_payment_method_script_handles() {
		$asset_path   = WGC_DIR_PATH . '/build/index.asset.php';
		$version      = WGC_VERSION;
		$dependencies = array();
		if ( file_exists( $asset_path ) ) {
			$asset        = require $asset_path;
			$version      = is_array( $asset ) && isset( $asset['version'] )
				? $asset['version']
				: $version;
			$dependencies = is_array( $asset ) && isset( $asset['dependencies'] )
				? $asset['dependencies']
				: $dependencies;
		}

		wp_enqueue_style(
			'wc-gateway-converge-blocks-integration',
			plugins_url( 'build/style-index.css', WGC_MAIN_FILE ),
			array(),
			$version,
			'all'
		);

		wp_register_script(
			'wc-gateway-converge-blocks-integration',
			plugins_url( 'build/index.js', WGC_MAIN_FILE ),
			$dependencies,
			$version,
			true
		);
		wp_set_script_translations(
			'wc-gateway-converge-blocks-integration',
			'elavon-converge-gateway'
		);
		return array( 'wc-gateway-converge-blocks-integration' );
	}

	/**
	 * Returns an array of key=>value pairs of data made available to the payment methods script.
	 *
	 * @return array
	 */
	public function get_payment_method_data() {
		return array(
			'title'                  => $this->get_setting( WGC_KEY_TITLE ),
			'description'            => $this->get_gateway()->get_description(),
			'supports'               => $this->get_supported_features(),
			'showSavedCards'         => $this->should_show_saved_cards(),
			'showSaveOption'         => $this->should_show_save_option(),
			'hasSubscriptionInCart'  => wgc_has_subscription_elements_in_cart(),
			'subscriptionDisclosure' => $this->get_gateway()->get_option( WGC_KEY_SUBSCRIPTIONS_DISCLOSURE_MESSAGE ) ?? '',
		);
	}

	/**
	 * Returns an array of supported features.
	 *
	 * @return string[]
	 */
	public function get_supported_features() {
		return $this->get_gateway()->supports ?? array();
	}

	/**
	 * Determine if store should show saved cards at checkout.
	 *
	 * @return bool True if store should show saved cards at checkout.
	 */
	private function should_show_saved_cards() {
		return $this->get_gateway()->isSavePaymentMethodsEnabled() ?? false;
	}

	/**
	 * Determine if store allows cards to be saved during checkout.
	 *
	 * @return bool True if merchant allows shopper to save cards during checkout.
	 */
	private function should_show_save_option() {
		$gateway = $this->get_gateway();
		return ( is_user_logged_in() && $gateway->isSavePaymentMethodsEnabled() && $gateway->can_store_one_more_card() );
	}

	/**
	 * Helper function to get and store an instance of the gateway
	 *
	 * @since x.x.x
	 * @return WC_Gateway_Converge|null
	 */
	private function get_gateway() {
		if ( empty( $this->gateway ) ) {
			$payment_gateways = WC()->payment_gateways->payment_gateways();
			$this->gateway    = $payment_gateways[ $this->name ];
		}

		return $this->gateway;
	}

	/**
	 * Manually add Elavon Converge tokens to the saved payment methods list.
	 *
	 * Elavon Converge tokens use the `gateway_converge_storedcard` token type instead of the `cc` token type.
	 * WooCommerce Blocks doesn't know how to display the `gateway_converge_storedcard` token type,
	 * so this converts all Elavon Converge saved cards to the standard `cc` token type
	 * and unsets the custom `gateway_converge_storedcard` token type.
	 *
	 * @param array $saved_methods The saved payment methods.
	 * @param int   $customer_id   The customer ID.
	 * @return array $saved_methods Modified saved payment methods.
	 */
	public function add_saved_payment_methods( $saved_methods, $customer_id ) {
		if ( ! $this->should_show_saved_cards() ) {
			unset( $saved_methods['gateway_converge_storedcard'] );
			return $saved_methods;
		}

		$tokens = $this->get_gateway()->get_tokens();

		if ( ! $tokens ) {
			$tokens = array();
		}

		foreach ( $tokens as $token ) {
			$saved_token = array(
				'method'     => array(
					'gateway' => WGC_PAYMENT_NAME,
					'last4'   => esc_html( $token->get_last4() ),
					'brand'   => esc_html( $token->get_card_scheme() ),
				),
				'expires'    => esc_html( $token->get_expiry_month() . '/' . substr( $token->get_expiry_year(), - 2 ) ),
				'is_default' => $token->is_default(),
				'actions'    => array(),
				'tokenId'    => $token->get_id(),
			);

			$saved_methods['cc'][] = $saved_token;
		}

		unset( $saved_methods['gateway_converge_storedcard'] );
		return $saved_methods;
	}
}
