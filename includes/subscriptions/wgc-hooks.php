<?php
defined( 'ABSPATH' ) || exit();


class Wgc_Hooks {

	public static function init() {
		add_action( 'woocommerce_account_view-converge-subscription_endpoint',
			array( __CLASS__, 'wgc_view_subscription_template' ) );
		add_action( 'woocommerce_account_converge-subscriptions_endpoint',
			array( __CLASS__, 'wgc_subscriptions_template' ) );
		add_action( 'woocommerce_account_converge-subscription-change-method_endpoint',
			array( __CLASS__, 'wgc_subscriptions_change_method_template' ) );
		add_action( 'woocommerce_account_converge-change-card-details_endpoint',
			array( __CLASS__, 'wgc_change_card_details_template' ) );
		add_filter( 'woocommerce_account_menu_items', array( __CLASS__, 'wgc_account_menu_items' ), 10, 2 );
		add_action( 'woocommerce_order_details_after_order_table', array( __CLASS__, 'wgc_order_details' ) );
		add_action( 'init', array( __CLASS__, 'add_subscriptions_endpoint' ) );
		add_action( 'woocommerce_order_actions', array( __CLASS__, 'add_subscription_actions' ) );
		add_action( 'woocommerce_order_action_wgc_cancel_subscription',
			array( __CLASS__, 'cancel_subscription_handler' ) );
		add_filter( 'woocommerce_email_recipient_customer_processing_order', array( __CLASS__, 'wgc_subscription_email' ), 10, 2 );
		add_filter( 'request', array( __CLASS__, 'request_query' ) );
	}

	public static function request_query( $query_vars ) {
		if ( is_admin() ) {
			if ( ! function_exists( 'get_current_screen' ) ) {
				require_once ABSPATH . '/wp-admin/includes/screen.php';
			}
			$screen    = get_current_screen();
			$screen_id = $screen ? $screen->id : '';
			if ( $screen_id === 'edit-wgc_subscription' ) {
				if ( empty( $query_vars['post_status'] ) ) {
					$query_vars['post_status'] = array_keys( wc_get_order_statuses() );
				}
			}
		}

		return $query_vars;
	}

	public static function add_subscriptions_endpoint() {
		$endpoints = array(
			'converge-subscriptions',
			'view-converge-subscription',
			'converge-subscription-change-method',
			'converge-change-card-details',
		);

		foreach ( $endpoints as $endpoint ) {
			add_rewrite_endpoint( $endpoint, EP_ROOT | EP_PAGES );
		}
	}

	public static function wgc_change_card_details_template($id) {

		$payment_token = WC_Payment_Tokens::get( $id );

		if ( ! $payment_token || $payment_token->get_user_id() !== get_current_user_id() ) {
			self::print_error( __( 'Invalid card.', 'elavon-converge-gateway' ) );

			return;
		}
		if ( isset( $_POST['elavon-converge-gateway-card-expiry'] ) && isset( $_POST['elavon-converge-gateway-card-cvc'] ) ) {
			wc_nocache_headers();

			$nonce_value = wc_get_var( $_REQUEST['wgc-edit-payment-method-nonce'], wc_get_var( $_REQUEST['_wpnonce'], '' ) ); // @codingStandardsIgnoreLine.

			if ( ! wp_verify_nonce( $nonce_value, 'wgc-edit-payment-method' ) ) {
				return;
			}

			$stored_card = $payment_token->get_token( wgc_get_payment_name() );
			$expiry = wgc_format_card_expiration_date( wc_clean( $_POST['elavon-converge-gateway-card-expiry'] ) );

			if ( ! wgc_get_gateway()->getC2ApiService()->canConnect() ) {
				self::print_error( __( 'Payment method cannot be updated due to a connection issue.',
					'elavon-converge-gateway' ) );

				return;
			}

			$response = wgc_get_gateway()->get_converge_api()->update_stored_card_details( $stored_card,
				$expiry['month'],
				$expiry['year'],
				wc_clean( $_POST['elavon-converge-gateway-card-cvc'] ) );

			if ( $response ) {
				$payment_token->init_from_stored_card( $response );
				$payment_token->save();
				self::print_notice( __( 'Payment method successfully updated.', 'elavon-converge-gateway' ) );
			} else {
				wc_print_notices();
			}
		}

		wgc_get_template( 'myaccount/change-card-details.php',
			array( 'payment_token' => $payment_token ) );
	}

	public static function wgc_subscriptions_template() {
		$subscriptions = wgc_get_subscriptions_for_user( get_current_user_id() );
		wgc_get_template( 'myaccount/my-subscriptions.php',
			array( 'subscriptions' => $subscriptions ) );
	}

	public static function wgc_subscriptions_change_method_template( $id ) {
		/** @var WC_Converge_Subscription $subscription */
		if ( ! $subscription = wc_get_order( $id ) ) {
			self::print_error( __( 'Invalid subscription.', 'elavon-converge-gateway' ) );

			return;
		} elseif ( get_current_user_id() !== $subscription->get_user_id() || ! $subscription->is_cancellable() ) {
			self::print_error( __( 'Invalid subscription.', 'elavon-converge-gateway' ) );

			return;
		}

		$converge_subscription = wgc_get_gateway()->get_converge_api()->get_subscription( $subscription->get_transaction_id() );

		if ( ! $converge_subscription->isSuccess() ) {
			self::print_error( __( 'Some of your subscription data is unavailable at this time. Please try again later.',
				'elavon-converge-gateway' ) );

			return;
		} else {
			wc_add_notice( __( 'Please choose a new payment method.', 'elavon-converge-gateway' ), 'notice' );

			wgc_get_template(
				'myaccount/change-payment-method.php',
				array(
					'subscription'          => $subscription,
					'converge_subscription' => $converge_subscription,
					'available_gateways'    => WC()->payment_gateways()->get_available_payment_gateways(),
				)
			);
			wp_register_script( 'woocommerce_converge_save_timezone',
				plugins_url( 'assets/js/save_timezone.js', WGC_MAIN_FILE ) );
			wp_enqueue_script( 'woocommerce_converge_save_timezone' );
			wp_enqueue_style( 'woocommerce_converge_checkout_css',
				plugins_url( 'assets/css/elavon_convergegateway.css', WGC_MAIN_FILE ) );
		}
	}


	public static function wgc_account_menu_items( $items, $endpoints = array() ) {
		$endpoints = array( 'converge-subscriptions' => __( 'Converge Subscriptions', 'elavon-converge-gateway' ) );

		if ( isset( $items['orders'] ) ) {
			$position = array_search( 'orders', array_keys( $items ) );
			$items    = array_merge( array_slice( $items, 0, $position + 1 ),
				$endpoints,
				array_slice( $items, $position + 1 ) );
		} else {
			$items = array_merge( $items, $endpoints );
		}

		return $items;
	}

	public static function wgc_view_subscription_template( $id ) {
		/** @var WC_Converge_Subscription $subscription */
		if ( ! $subscription = wc_get_order( $id ) ) {
			self::print_error( __( 'Invalid subscription.', 'elavon-converge-gateway' ) );

			return;
		} elseif ( get_current_user_id() !== $subscription->get_user_id() ) {
			self::print_error( __( 'Invalid subscription.', 'elavon-converge-gateway' ) );

			return;
		}

		if ( isset( $_POST['cancel'] ) && isset( $_POST['cancel_wpnonce'] ) ) {
			if ( wp_verify_nonce( $_POST['cancel_wpnonce'], 'cancel-subscription-' . $subscription->get_transaction_id() )
					&& $_POST['cancel_wpnonce'] != WC()->session->get( 'cancel_wpnonce' ) ) {

				WC()->session->set( 'cancel_wpnonce', $_POST['cancel_wpnonce'] );
				if ( wgc_get_gateway()->get_converge_api()->cancel_subscription( $subscription )->isSuccess() ) {
					self::print_notice( __( 'Your subscription has been cancelled.', 'elavon-converge-gateway' ) );
				} else {
					self::print_error( __( 'Your subscription could not be cancelled.', 'elavon-converge-gateway' ) );
				}
			} elseif ( $_POST['cancel_wpnonce'] == WC()->session->get( 'cancel_wpnonce' ) ) {
				self::print_error( __( 'There has been an error. Please reload the page.', 'elavon-converge-gateway' ) );
			} else {
				self::print_error( __( 'Your session has expired. Please reload the page.', 'elavon-converge-gateway' ) );
			}
		}

		if (empty($subscription->get_transaction_id())) {
			self::print_error(__('The subscription data is unavailable. Reason: failed to create transaction.',
				'elavon-converge-gateway'));

			return;
		}

		$converge_subscription = wgc_get_gateway()->get_converge_api()->get_subscription( $subscription->get_transaction_id() );
		if ( ! $converge_subscription->isSuccess() ) {
			self::print_error( __( 'Some of your subscription data is unavailable at this time. Please try again later.',
				'elavon-converge-gateway' ) );

			return;
		} else {
			wgc_get_template( 'myaccount/view-subscription.php',
				array( 'subscription' => $subscription, 'converge_subscription' => $converge_subscription ) );
		}
		wp_register_script( 'woocommerce_converge_cancel_subscription', plugins_url( 'assets/js/cancel_subscription.js', WGC_MAIN_FILE ) );
		wp_enqueue_script( 'woocommerce_converge_cancel_subscription' );
		$params = [ 'cancel_alert' => __( 'Cancelling this subscription will stop all upcoming scheduled payments from processing.', 'elavon-converge-gateway' ) ];
		wp_localize_script( 'woocommerce_converge_cancel_subscription', 'elavon_converge_gateway', $params );
	}

	public static function print_error( $error ) {
		wc_add_notice( $error, 'error' );
		wc_print_notices();
	}

	public static function print_notice( $notice ) {
		wc_add_notice( $notice );
		wc_print_notices();
	}

	public static function wgc_order_details( $order ) {
		if ( ! is_object( $order ) ) {
			$order = wc_get_order( $order );
		}

		if ( $subscriptions = wgc_get_subscriptions_for_order( $order ) ) {
			wgc_get_template( 'order/subscription-details.php', array( 'subscriptions' => $subscriptions ) );
		}
	}

	public static function add_subscription_actions( $actions ) {
		global $theorder;

		if ( $theorder->get_type() !== WGC_SUBSCRIPTION_POST_TYPE ) {
			return $actions;
		}

		if ( $theorder->is_cancellable() ) {
			$actions = self::add_cancel_subscription_action( $actions, $theorder );
		}

		return $actions;
	}

	public static function add_cancel_subscription_action( $actions, $theorder ) {
		$actions ['wgc_cancel_subscription'] = __( 'Cancel Subscription', 'elavon-converge-gateway' );

		return $actions;
	}

	public static function cancel_subscription_handler( $order ) {
		$cancel_response = wgc_get_gateway()->get_converge_api()->cancel_subscription( $order );
		if ( $cancel_response->isSuccess() ) {
			$order->add_order_note( sprintf( __( 'Subscription was cancelled.', 'elavon-converge-gateway' ) ) );
			$order->update_status( 'cancelled' );
		} else {
			$order->add_order_note( wgc_get_order_error_note( __( 'There was an error while canceling the subscription.',
				'elavon-converge-gateway' ),
				$cancel_response ) );
		}
	}

	public static function wgc_subscription_email( $recipients, $order_object ) {

		if ( $order_object && $order_object->get_type() == WGC_SUBSCRIPTION_POST_TYPE ) {
			return null;
		}

		return $recipients;
	}
}

Wgc_Hooks::init();