<?php

use Elavon\Converge2\DataObject\TimeUnit;
use Automattic\WooCommerce\Utilities\OrderUtil;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

class WC_Meta_Box_Wgc_Subscription_Data {

	public static function init() {
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ), 50 );
		add_filter( 'product_type_selector', array( __CLASS__, 'add_type' ) );
		add_filter( 'product_type_options', array( __CLASS__, 'product_type_options' ) );
		add_filter( 'woocommerce_product_data_tabs', array( __CLASS__, 'product_data_tabs' ) );
		add_action( 'woocommerce_product_options_general_product_data', array( __CLASS__, 'output_general_data' ) );
		add_action( 'woocommerce_product_after_variable_attributes', array( __CLASS__, 'output_variation_data' ), 10, 3 );
		add_action( 'woocommerce_process_product_meta_converge-subscription',
			array( __CLASS__, 'save_subscription_data' ) );
		add_action( 'woocommerce_save_product_variation', array( __CLASS__, 'save_variation_data' ), 10, 2 );
		add_action( 'admin_notices', array( __CLASS__, 'admin_notices' ) );
		add_action( 'init', array( __CLASS__, 'action_init' ) );
		add_action( 'add_meta_boxes', array( __CLASS__, 'add_meta_boxes' ), 40, 2 );
	}

	public static function add_meta_boxes( $post_type, $post ) {
		if ( 'wgc_subscription' === $post_type || 'woocommerce_page_wc-orders--wgc_subscription' === $post_type ) {
			add_meta_box( 'wgc-converge-subscription-details', __( 'Converge Subscription Details', 'elavon-converge-gateway' ), array(
				__CLASS__,
				'converge_subscription_details_view'
			), array( 'wgc_subscription', 'woocommerce_page_wc-orders--wgc_subscription' ), 'normal', 'default' );
			add_meta_box( 'wgc-related-order', __( 'Related Orders', 'elavon-converge-gateway' ), array(
				__CLASS__,
				'related_order_view'
			), array( 'wgc_subscription', 'woocommerce_page_wc-orders--wgc_subscription' ), 'normal', 'default' );
			add_meta_box( 'wgc-transaction-history', __( 'Transaction History', 'elavon-converge-gateway' ), array(
				__CLASS__,
				'transaction_history_view'
			), array( 'wgc_subscription', 'woocommerce_page_wc-orders--wgc_subscription' ), 'normal', 'default' );
		}

		if ( $post instanceof \WC_Order ) {
			$order_id = $post->get_id();
		} else {
			$order_id = $post->ID;
		}

		if ( OrderUtil::is_order( $order_id, wc_get_order_types() ) ) {
			$order         = wc_get_order( $order_id );
			$subscriptions = wgc_get_subscriptions_for_order( $order );

			if ( is_array( $subscriptions ) && count( $subscriptions ) > 0 ) {
				add_meta_box( 'wgc-related-subscriptions', __( 'Related Subscriptions', 'elavon-converge-gateway' ), array(
					__CLASS__,
					'related_subscriptions_view'
				), array( 'shop_order', 'woocommerce_page_wc-orders' ), 'normal', 'default' );
			}
		}
	}

	public static function related_subscriptions_view( $post ) {
		$order_id      = $post instanceof \WC_Order ? $post->get_id() : $post->ID;
		$order         = wc_get_order( $order_id );
		$subscriptions = wgc_get_subscriptions_for_order( $order );

		include 'views/html-related-subscriptions.php';
	}

	public static function converge_subscription_details_view($post) {
		$order_id            = $post instanceof \WC_Order ? $post->get_id() : $post->ID;
		$subscription        = wgc_get_subscription_object_by_id( $order_id );
		$subscription_txn_id = $subscription->get_transaction_id();
		$response            = wgc_get_gateway()->get_converge_api()->get_subscription($subscription_txn_id);

		if ($response && $response->isSuccess()) {
			$converge_subscription = $response->getData();
			include 'views/html-converge-subscription-details.php';
		} else {
			echo _e('There is no Converge Subscription added for this subscription.', 'elavon-converge-gateway');
		}
	}

	public static function transaction_history_view($post) {
		$order_id     = $post instanceof \WC_Order ? $post->get_id() : $post->ID;
		$subscription = wgc_get_subscription_object_by_id($order_id);
		$response     = wgc_get_gateway()->get_converge_api()->get_subscription_transactions($subscription);

		$has_errors = FALSE;
		$transactions = array();

		if ($response && $response->isSuccess()) {
			$transactions = $response->getItems();
		} else {
			$has_errors = TRUE;
		}

		include 'views/html-transaction-history.php';
	}

	public static function related_order_view($post) {
		$order_id = $post instanceof \WC_Order ? $post->get_id() : $post->ID;
		$orders   = wgc_get_subscription_related_orders( $order_id );

		include 'views/html-related-order.php';
	}

	public static function admin_notices() {
		$screen    = get_current_screen();
		$screen_id = $screen ? $screen->id : '';
		if ( $screen_id === 'product' ) {
			if ( $admin_notices = WC()->session->get( 'wgc_admin_notices' ) ) {
				foreach ( $admin_notices as $admin_notice ) {
					self::display_error( $admin_notice );
				}
				WC()->session->set( 'wgc_admin_notices', null );
			}
		}
	}

	public static function enqueue_scripts() {
		$screen    = get_current_screen();
		$screen_id = $screen ? $screen->id : '';

		if ( $screen_id === 'product' ) {

			wp_enqueue_style( 'wgc-meta-boxes-product',
				plugins_url( 'assets/css/admin/meta-boxes-product.css', WGC_MAIN_FILE ) );
			wp_enqueue_script( 'wgc-meta-boxes-product',
				plugins_url( 'assets/js/admin/meta-boxes-product.js', WGC_MAIN_FILE ),
				array(),
				false,
				true );
			wp_localize_script( 'wgc-meta-boxes-product',
				'elavon_converge_gateway',
				array( 'subscription_name' => WGC_SUBSCRIPTION_NAME ) );

		} else if ( is_admin() && $screen_id === WGC_SUBSCRIPTION_POST_TYPE || 'woocommerce_page_wc-orders--wgc_subscription' === $screen_id ) {

			wp_enqueue_script( 'wgc-subscription-details',
				plugins_url( 'assets/js/admin/subscription-details.js', WGC_MAIN_FILE ),
				array(),
				false,
				true );
		}
	}

	public static function add_type( $product_types ) {
		return array_merge(
			$product_types,
			array(
				'converge-subscription'          => __( 'Converge subscription', 'elavon-converge-gateway' ),
				WGC_VARIABLE_SUBSCRIPTION_NAME => __( 'Converge variable subscription', 'elavon-payment-gateway' ),
			)
		);
	}

	public static function output_general_data() {
		global $thepostid, $product_object;
		$post = get_post( $thepostid );
		include 'views/html-product-data-general.php';
	}

	public static function output_variation_data( $loop, $variation_data, $variation ) {
		include 'views/html-product-data-variations.php';
	}

	/**
	 *
	 * @param int $product_id
	 */
	public static function save_subscription_data( $product_id ) {
		$values_to_validate = array(
			'wgc_plan_price'                             => null,
			'wgc_plan_introductory_rate'                 => null,
			'wgc_plan_introductory_rate_amount'          => null,
			'wgc_plan_introductory_rate_billing_periods' => null,
			'wgc_plan_billing_frequency'                 => null,
			'wgc_plan_billing_frequency_count'           => null,
			'wgc_plan_billing_ending'                    => null,
			'wgc_plan_ending_billing_periods'            => null,
		);
		foreach ( array_keys( $values_to_validate ) as $field_name ) {
			if ( isset( $_POST[ $field_name ] ) ) {
				$values_to_validate[ $field_name ] = wc_clean( $_POST[ $field_name ] );
			}
		}

		$validator = new WC_Plan_Validator( $values_to_validate );
		$validator->validate( $values_to_validate );

		if ( $error_messages = $validator->getErrorMessages() ) {
			WC()->session->set( 'wgc_admin_notices', $error_messages );
		} else {
		    $properties = self::get_plan_properties_from_valid_data($values_to_validate);
			
			$product = new WC_Product_Converge_Subscription($product_id);
			$product->set_props( $properties );
			if ( empty( $product->get_changes() ) ) {
				return;
			}

			$connection_error = function () {
				WC()->session->set( 'wgc_admin_notices',
					array(
						__( 'The subscription details could not be saved due to Converge connection error. Please try again later.',
							'elavon-converge-gateway' ),
					) );
			};

			if ( $product_plan = $product->get_wgc_plan_id() ) {
				wgc_get_gateway()->delete_plan( $product_plan );
			}
			$plan_response = wgc_get_gateway()->get_converge_api()->create_product_plan( $product_id, $properties );

			if ( ! $plan_response->isSuccess() ) {
				WC()->session->set( 'wgc_admin_notices',
					array(
						__( 'The subscription details could not be saved due to Converge error. Please try again later.',
							'elavon-converge-gateway' ),
					) );

				return;
			} else {
				$product->set_wgc_plan_id( $plan_response->getId() );
				$product->save();
			}
		}
	}

	public static function save_variation_data( $variation_id, $i ) {
		if ( WGC_VARIABLE_SUBSCRIPTION_NAME === $_POST['product-type'] ) {
			$values_to_validate = array(
				'wgc_plan_price'                             => null,
				'wgc_plan_introductory_rate'                 => null,
				'wgc_plan_introductory_rate_amount'          => null,
				'wgc_plan_introductory_rate_billing_periods' => null,
				'wgc_plan_billing_frequency'                 => null,
				'wgc_plan_billing_frequency_count'           => null,
				'wgc_plan_billing_ending'                    => null,
				'wgc_plan_ending_billing_periods'            => null,
			);

			foreach ( array_keys( $values_to_validate ) as $field_name ) {
				if ( isset( $_POST[ $field_name ] ) ) {
					$values_to_validate[ $field_name ] = isset( $_POST[ $field_name ][ $i ] ) ? wc_clean( $_POST[ $field_name ][ $i ] ) : null;
				}
			}

			// reset regular and sale prices and leave subscription price instead
			if ( isset( $_POST['wgc_plan_price'][ $i ] ) ) {
				$subscription_price = wc_format_decimal( $_POST['wgc_plan_price'][ $i ] );
				update_post_meta( $variation_id, '_sale_price', '' );
				update_post_meta( $variation_id, '_regular_price', $subscription_price );
			}

			if ( $values_to_validate['wgc_plan_introductory_rate_amount'] == 0 ) {
				$values_to_validate['wgc_plan_introductory_rate_amount'] = null;
			}

			$validator = new WC_Plan_Validator( $values_to_validate );
			$validator->validate( $values_to_validate );

			if ( $error_messages = $validator->getErrorMessages() ) {
				foreach ( $error_messages as $error_message ) {
					self::display_error( $error_message );
				}
			} else {
				$properties = self::get_plan_properties_from_valid_data($values_to_validate);
				$variation = new WC_Product_Converge_Subscription_Variation( $variation_id );
				$variation->set_props( $properties );

				if ( $product_plan = $variation->get_wgc_plan_id() ) {
					wgc_get_gateway()->delete_plan( $product_plan );
				}
				$plan_response = wgc_get_gateway()->get_converge_api()->create_product_plan( $variation->get_parent_id(),
					$properties );
				if ( ! $plan_response->isSuccess() ) {
					self::display_error( 'The subscription details could not be saved due to Converge error. Please try again later.' );

					return;
				} else {
					$variation->set_wgc_plan_id( $plan_response->getId() );
					$variation->save();

					WC_Product_Converge_Variable_Subscription::sync_product( $variation->get_parent_id() );
				}
			}
		}
	}

	public static function product_type_options( $options ) {
		$options['virtual']['wrapper_class']      = $options['virtual']['wrapper_class'] . ' show_if_converge-subscription';
		$options['downloadable']['wrapper_class'] = $options['virtual']['wrapper_class'] . ' show_if_converge-subscription';

		return $options;
	}

	public static function product_data_tabs( $tabs ) {
		$tabs['inventory']['class'][] = 'show_if_converge-subscription';
		$tabs['inventory']['class'][]  = 'show_if_converge-variable-subscription';
		$tabs['variations']['class'][] = 'show_if_converge-variable-subscription';
		return $tabs;
	}

	public static function action_init() {
		if ( ! isset( WC()->session ) ) {
			WC()->initialize_session();
		}

		if (is_admin()){
			add_action('wp_ajax_wgc_create_order_ajax_action', array( __CLASS__, 'wgc_create_order_ajax_action' ) );
			add_action('wp_ajax_wgc_sync_subscription_ajax_action', array( __CLASS__, 'wgc_sync_subscription_ajax_action' ) );
		}

		if (isset($_POST['action'])) {
		    if ($_POST['action'] == 'wgc_create_order_ajax_action'){
			    self::wgc_create_order_ajax_action();
            }

			if ($_POST['action'] == 'wgc_sync_subscription_ajax_action'){
				self::wgc_sync_subscription_ajax_action();
			}
		}
	}

	public static function wgc_create_order_ajax_action() {

		global $wpdb;
		$response = array(
			'success' => false,
			'message' => '',
		);

		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], "wgc_new_order_txn_nonce" ) ) {
			$response['message'] = __( 'Invalid request. Nonce validation failed.', 'elavon-converge-gateway' );
			die( json_encode( $response ) );
		}

		if ( ! isset( $_POST['new_order_transaction_id'] ) || empty( $_POST['new_order_transaction_id'] ) ) {
			$response['message'] = __( 'Invalid Transaction Id.', 'elavon-converge-gateway' );
			die( json_encode( $response ) );
		}

		if ( ! isset( $_POST['subscription_id'] ) || empty( $_POST['subscription_id'] ) ) {
			$response['message'] = __( 'Invalid Subscription Id.', 'elavon-converge-gateway' );
			die( json_encode( $response ) );
		}

		$new_order_transaction_id = trim( $_POST['new_order_transaction_id'] );
		$subscription             = wgc_get_subscription_object_by_id( $_POST['subscription_id'] );

		$order_id = wgc_get_order_by_transaction_id($new_order_transaction_id);

		if ( $order_id ) {
			$response['message'] = '';
			$response['message'] = sprintf( __( 'We already have an Order (#%1$s) for this Transaction Id (%2$s).', 'elavon-converge-gateway' ), $order_id, $new_order_transaction_id );
			die( json_encode( $response ) );
		}

		$new_order = wgc_create_order_from_subscription( $subscription, $new_order_transaction_id );

		if ( ! $new_order ) {
			$response['message'] = __( 'There was an error creating the order.', 'elavon-converge-gateway' );
			$subscription->add_order_note( sprintf( __( 'There was an error creating the order. Transaction id: %1$s.', 'elavon-converge-gateway' ), $new_order_transaction_id ) );

			die( json_encode( $response ) );
		} else {
			$response['success'] = true;
			$response['message'] = sprintf( __( 'The renewal order #%1$s has been added for the Transaction Id %2$s.', 'elavon-converge-gateway' ), $new_order->get_id(), $new_order_transaction_id );

			$subscription->add_order_note( sprintf( __( 'Create renewal order requested by admin action. Transaction id: %1$s.', 'elavon-converge-gateway' ), $new_order_transaction_id ) );

			die( json_encode( $response ) );
		}
	}

	public static function wgc_sync_subscription_ajax_action() {
		$display = false;

		if ( isset( $_POST['form_data'] ) ) {

			if ( ! isset( $_POST['form_data']['subscription_id'] ) ) {
				return;
			}

			/** @var WC_Converge_Subscription $subscription */
			if ( ! $subscription = wc_get_order( $_POST['form_data']['subscription_id'] ) ) {

				return;
			}

			$converge_subscription = wgc_get_gateway()->get_converge_api()->get_subscription( $subscription->get_transaction_id() );

			if ( ! $converge_subscription->isSuccess() ) {

				return;
			}

			$status               = $subscription->get_status();
			$converge_status      = $converge_subscription->getSubscriptionState()->getValue();
			$corresponding_status = wgc_get_subscription_woo_status( $converge_status );
			if ( $status != $corresponding_status ) {
				if ( $_POST['form_data']['update'] == 'true' ) {
					$subscription->set_status( $corresponding_status );
					$subscription->save();
				} else {
					$display =  true;
				}
			}
		}

		echo json_encode(array('display'=> $display));

		exit;
	}

	public static function display_error( $admin_notice ) {
		?>
        <div class="notice is-dismissible notice-error wgc-notice">
            <p><?php _e( $admin_notice, 'elavon-converge-gateway' ); ?></p>
        </div>
		<?php
	}

	public static function get_plan_properties_from_valid_data( array $values_to_validate ) {
		$properties = array();

		$properties['wgc_plan_price']                   = $values_to_validate['wgc_plan_price'];
		$properties['wgc_plan_billing_frequency']       = $values_to_validate['wgc_plan_billing_frequency'];
		$properties['wgc_plan_billing_ending']          = $values_to_validate['wgc_plan_billing_ending'];
		$properties['wgc_plan_billing_frequency_count'] = $values_to_validate['wgc_plan_billing_frequency_count'];
		$properties['wgc_plan_introductory_rate']       = $values_to_validate['wgc_plan_introductory_rate'];

		if ( $values_to_validate['wgc_plan_introductory_rate'] == 'yes' ) {
			$properties['wgc_plan_introductory_rate_amount']          = $values_to_validate['wgc_plan_introductory_rate_amount'];
			$properties['wgc_plan_introductory_rate_billing_periods'] = $values_to_validate['wgc_plan_introductory_rate_billing_periods'];
		} else {
			$properties['wgc_plan_introductory_rate']                 = 'no';
			$properties['wgc_plan_introductory_rate_amount']          = '';
			$properties['wgc_plan_introductory_rate_billing_periods'] = '';
		}

		if ( $values_to_validate['wgc_plan_billing_ending'] == 'billing_periods' ) {
			$properties['wgc_plan_ending_billing_periods'] = $values_to_validate['wgc_plan_ending_billing_periods'];
		} else {
			$properties['wgc_plan_ending_billing_periods'] = '';
		}

		return $properties;
	}
}

WC_Meta_Box_Wgc_Subscription_Data::init();
