<?php
defined( 'ABSPATH' ) || exit();

if ( ! class_exists( 'WC_Product_Simple' ) ) {
	return;
}

class WC_Product_Converge_Variable_Subscription extends WC_Product_Variable {

	/**
	 *
	 * @since 2.6.2
	 */
	public static function init() {
		add_filter( 'woocommerce_data_stores', __CLASS__ . '::add_data_store' );
	}

	public function __construct( $product ) {
		$this->extra_data['min_subscription_price']              = 0;
		$this->extra_data['max_subscription_price']              = 0;
		$this->extra_data['min_subscription_price_variation_id'] = 0;
		$this->extra_data['max_subscription_price_variation_id'] = 0;

		parent::__construct( $product );

		$this->post = get_post( $this->id );
	}

	public static function add_data_store( $stores ) {
		$stores['product-converge-variable-subscription'] = 'WC_Product_Variable_Data_Store_CPT';
		return $stores;
	}

	public function get_type() {
		return WGC_VARIABLE_SUBSCRIPTION_NAME;
	}

	public function get_product_type() {
		return $this->product_type;
	}

	public function __get( $key ) {
		return $this->get_prop( $key );
	}

	public function __set( $key, $value ) {
		$this->set_prop( $key, $value );
	}

	public function get_price_html( $price = '' ) {
		if ( $this->sync_required() ) {
			self::sync_product( $this );
		}

		if ( $this->min_subscription_price_variation_id !== $this->max_subscription_price_variation_id ) {
			$low_variation  = $this->get_child( $this->min_subscription_price_variation_id );
			$high_variation = $this->get_child( $this->max_subscription_price_variation_id );

			$text = sprintf( __( 'From %1$s to %2$s', 'elavon-converge-gateway' ), wp_kses_post( $low_variation->get_price_html() ), wp_kses_post( $high_variation->get_price_html() ) );
		} else {
			$variation = $this->get_child( $this->min_subscription_price_variation_id );
			$text      = $variation->get_price_html();
		}
		return $text;
	}

	public function sync_required() {
		return ! $this->min_subscription_price || ! $this->max_subscription_price;
	}

	public function get_child( $child_id ) {
		return new WC_Product_Converge_Subscription_Variation( $child_id );
	}

	public static function sync_product( $product, $save = true ) {
		$product  = new self( $product );
		$children = get_posts(
			array(
				'post_parent'    => $product->get_id(),
				'posts_per_page' => - 1,
				'post_type'      => 'product_variation',
				'post_status'    => 'publish',
			)
		);

		if ( ! $children ) {
			return;
		}

		$price_types = array( 'wgc_plan_price' );

		foreach ( $price_types as $price_type ) {

			$min_price_type              = null;
			$max_price_type              = null;
			$min_price_type_variation_id = null;
			$max_price_type_variation_id = null;

			foreach ( $children as $child ) {

				$child_price = get_post_meta( $child->ID, '_' . $price_type, true );

				// if the min price_type is null or it's greater than the child_pricetype, change it's value to the
				// child_price.
				if ( is_null( $min_price_type ) || $min_price_type > $child_price ) {
					$min_price_type              = $child_price;
					$min_price_type_variation_id = $child->ID;
				}

				if ( is_null( $max_price_type ) || $max_price_type < $child_price ) {
					$max_price_type              = $child_price;
					$max_price_type_variation_id = $child->ID;
				}
			}

			$product->{'set_min_subscription_price'}( $min_price_type );
			$product->{'set_max_subscription_price'}( $max_price_type );
			$product->{'set_min_subscription_price_variation_id'}( $min_price_type_variation_id );
			$product->{'set_max_subscription_price_variation_id'}( $max_price_type_variation_id );
			$product->save();
		}
	}

	public function set_min_subscription_price( $price ) {
		$this->min_subscription_price = $price;
	}

	public function set_max_subscription_price( $price ) {
		$this->max_subscription_price = $price;
	}

	public function set_min_subscription_price_variation_id( $id ) {
		$this->min_subscription_price_variation_id = $id;
	}

	public function set_max_subscription_price_variation_id( $id ) {
		$this->max_subscription_price_variation_id = $id;
	}

	public function set_subscription_one_time_shipping( $one_time_shipping ) {
		$this->subscription_one_time_shipping = $one_time_shipping;
	}

	public function get_min_subscription_price() {
		return $this->min_subscription_price;
	}

	public function get_max_subscription_price() {
		return $this->max_subscription_price;
	}

	public function get_min_subscription_price_variation_id() {
		return $this->min_subscription_price_variation_id;
	}

	public function get_max_subscription_price_variation_id() {
		return $this->max_subscription_price_variation_id;
	}

	public function get_subscription_one_time_shipping() {
		return $this->subscription_one_time_shipping;
	}
}
WC_Product_Converge_Variable_Subscription::init();
