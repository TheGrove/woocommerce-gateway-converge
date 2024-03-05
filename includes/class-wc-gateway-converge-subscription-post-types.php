<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_Gateway_Converge_Subscription_Post_Types {

	public static function init() {
		add_action( 'woocommerce_after_register_post_type', array( __CLASS__, 'register_posts' ) );
	}

	public static function register_posts() {
		if ( ! wgc_subscriptions_active() ) {
			return;
		}

		wc_register_order_type(
			WGC_SUBSCRIPTION_POST_TYPE,
			array(
				'label'                            => __( 'Subscription', 'elavon-converge-gateway' ),
				'labels'                           => array(
					'name'                  => __( 'Subscriptions', 'elavon-converge-gateway' ),
					'singular_name'         => _x(
						'Subscription',
						'wgc_subscription post type singular name',
						'elavon-converge-gateway'
					),
					'edit'                  => __( 'Edit', 'elavon-converge-gateway' ),
					'edit_item'             => __( 'Edit Subscription', 'elavon-converge-gateway' ),
					'new_item'              => __( 'New Subscription', 'elavon-converge-gateway' ),
					'view'                  => __( 'View Subscription', 'elavon-converge-gateway' ),
					'view_item'             => __( 'View Subscription', 'elavon-converge-gateway' ),
					'search_items'          => __( 'Search Subscriptions', 'elavon-converge-gateway' ),
					'not_found'             => __( 'No Subscriptions found', 'elavon-converge-gateway' ),
					'not_found_in_trash'    => __( 'No Subscriptions found in trash', 'elavon-converge-gateway' ),
					'parent'                => __( 'Parent Orders', 'elavon-converge-gateway' ),
					'menu_name'             => _x(
						'Converge Subscriptions',
						'Admin menu name',
						'elavon-converge-gateway'
					),
					'filter_items_list'     => __( 'Filter subscriptions', 'elavon-converge-gateway' ),
					'items_list_navigation' => __( 'Subscriptions navigation', 'elavon-converge-gateway' ),
					'items_list'            => __( 'Subscriptions list', 'elavon-converge-gateway' ),
				),
				'capabilities'                     => array( 'create_posts' => false ),
				'description'                      => __(
					'Subscription made through the Elavon Converge Gateway.',
					'elavon-converge-gateway'
				),
				'public'                           => false,
				'show_ui'                          => true,
				'capability_type'                  => 'shop_order',
				'map_meta_cap'                     => true,
				'publicly_queryable'               => false,
				'exclude_from_search'              => true,
				'show_in_menu'                     => current_user_can( 'manage_woocommerce' ) ? 'woocommerce' : true,
				'hierarchical'                     => false,
				'show_in_nav_menus'                => false,
				'rewrite'                          => false,
				'query_var'                        => false,
				'supports'                         => array( 'title' ),
				'has_archive'                      => false,
				'exclude_from_orders_screen'       => true,
				'add_order_meta_boxes'             => true,
				'exclude_from_order_count'         => true,
				'exclude_from_order_views'         => true,
				'exclude_from_order_webhooks'      => true,
				'exclude_from_order_reports'       => true,
				'exclude_from_order_sales_reports' => true,
				'class_name'                       => 'WC_Converge_Subscription',
			)
		);
	}
}

WC_Gateway_Converge_Subscription_Post_Types::init();
