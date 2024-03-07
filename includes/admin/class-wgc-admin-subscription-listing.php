<?php
defined( 'ABSPATH' ) || exit();

use Automattic\WooCommerce\Utilities\OrderUtil;

class WGC_Admin_Subscription_Listing {

	public function __construct() {
		add_action(
			'manage_' . WGC_SUBSCRIPTION_POST_TYPE . '_posts_custom_column',
			array(
				$this,
				'subscription_custom_column'
			)
		);

		add_filter(
			'manage_' . WGC_SUBSCRIPTION_POST_TYPE . '_posts_columns',
			array(
				$this,
				'subscription_columns'
			)
		);

		add_action(
			'manage_woocommerce_page_wc-orders--' . WGC_SUBSCRIPTION_POST_TYPE . '_custom_column',
			array(
				$this,
				'subscription_custom_column_hpos'
			),
			10,
			2
		);

		add_filter(
			'woocommerce_' . WGC_SUBSCRIPTION_POST_TYPE . '_list_table_columns',
			array(
				$this,
				'subscription_columns'
			)
		);
	}

	/**
	 * Add custom columns to the subscription listing.
	 *
	 * @param array $existing_columns Existing columns.
	 */
	public function subscription_columns( $existing_columns ) {
		if ( OrderUtil::custom_orders_table_usage_is_enabled() ) {
			unset( $existing_columns['order_number'] );
			unset( $existing_columns['order_date'] );
			unset( $existing_columns['order_status'] );
			unset( $existing_columns['billing_address'] );
			unset( $existing_columns['shipping_address'] );
			unset( $existing_columns['order_total'] );
			unset( $existing_columns['wc_actions'] );
		} else {
			unset( $existing_columns['title'] );
			unset( $existing_columns['date'] );
			unset( $existing_columns['comments'] );
		}

		$columns = array(
			'cb'           => '<input type="checkbox"/>',
			'subscription' => __( 'Subscription', 'elavon-converge-gateway' ),
			'order'        => __( 'Parent Order', 'elavon-converge-gateway' ),
			'items'        => __( 'Items', 'elavon-converge-gateway' ),
			'total'        => __( 'Total', 'elavon-converge-gateway' ),
		);

		return wp_parse_args( $existing_columns, $columns );
	}

	public function subscription_custom_column( $column ) {
		global $post;
		$subscription = wc_get_order( $post->ID );

		$this->render_columns( $column, $subscription );
	}

	/**
	 * Handles output for the default column.
	 *
	 * @param string    $column Identifier for the custom column.
	 * @param \WC_Order $order  Current WooCommerce order object.
	 */
	public function render_columns( $column, $order ) {
		$user = get_user_by( 'id', $order->get_customer_id() );

		switch ( $column ) {
			case 'subscription':
				if ( empty( $user ) ) {
					printf( '<a href="%s"><strong>#%s</strong></a>', $order->get_edit_order_url(), $order->get_id() );
				} else {
					printf( '<a href="%s"><strong>#%s</strong></a> %s <a href="%s">%s %s</a>', $order->get_edit_order_url(), $order->get_id(), __( 'for', 'elavon-converge-gateway' ), get_edit_user_link( $user->ID ), $user->user_firstname, $user->user_lastname );
				}
				break;
			case 'order':
				printf( '<a href="%s"><strong>#%s</strong></a>', get_edit_post_link( $order->get_parent_id() ), $order->get_parent_id() );
				break;
			case 'items':
				foreach ( $order->get_items() as $item_id => $item ) {
					$product = wc_get_product( $item['product_id'] );
					printf( '<div class="order-item"><a href="%s">%s</a></div>', get_edit_post_link( $item['product_id'] ), $product ? $product->get_title() : '' );
				}
				break;
			case 'total':
				echo wc_price( $order->get_total() );
				break;
		}
	}

	/**
	 * Handles output for the default column.
	 *
	 * @param string    $column       Identifier for the custom column.
	 * @param \WC_Order $subscription Current WooCommerce subscription order object.
	 */
	public function subscription_custom_column_hpos( $column, $subscription ) {
		$this->render_columns( $column, $subscription );
	}
}

new WGC_Admin_Subscription_Listing();
