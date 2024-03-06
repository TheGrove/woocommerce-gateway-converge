<?php
defined( 'ABSPATH' ) || exit();


class WGC_Admin_Subscription_Listing {

	public function __construct() {
		add_action(
			'manage_' . WGC_SUBSCRIPTION_POST_TYPE . '_posts_custom_column',
			array(
				$this,
				'subscription_custom_column',
			)
		);
		add_filter( 'manage_' . WGC_SUBSCRIPTION_POST_TYPE . '_posts_columns', array( $this, 'subscription_columns' ) );
	}

	public function subscription_columns( $existing_columns ) {
		unset( $existing_columns['title'] );
		unset( $existing_columns['date'] );
		unset( $existing_columns['comments'] );
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
		$user         = get_user_by( 'id', $subscription->get_customer_id() );

		switch ( $column ) {
			case 'subscription':
				if ( empty( $user ) ) {
					printf( '<a href="%s"><strong>#%s</strong></a>', esc_url( get_edit_post_link( $post->ID ) ), esc_html( post->ID ) );
				} else {
					printf(
						'<a href="%s"><strong>#%s</strong></a> %s <a href="%s">%s %s</a>',
						esc_url( get_edit_post_link( $post->ID ) ),
						esc_html( $subscription->get_id() ),
						esc_html__( 'for', 'elavon-converge-gateway' ),
						esc_url( get_edit_user_link( $user->ID ) ),
						esc_html( $user->user_firstname ),
						esc_html( $user->user_lastname )
					);
				}
				break;
			case 'order':
				printf( '<a href="%s"><strong>#%s</strong></a>', esc_url( get_edit_post_link( $subscription->get_parent_id() ) ), esc_html( $subscription->get_parent_id() ) );
				
				break;
			case 'items':
				foreach ( $subscription->get_items() as $item_id => $item ) {
					$product = wc_get_product( $item['product_id'] );
					printf( '<div class="order-item"><a href="%s">%s</a></div>', esc_url( get_edit_post_link( $item['product_id'] ) ), $product ? esc_html( $product->get_title() ) : '' );
				}
				break;
			case 'total':
				echo wp_kses_post( wc_price( $subscription->get_total() ) );

				break;
		}
	}
}

new WGC_Admin_Subscription_Listing();
