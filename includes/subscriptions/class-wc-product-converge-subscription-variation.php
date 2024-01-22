<?php
defined( 'ABSPATH' ) || exit();

if ( ! class_exists( 'WC_Product_Variation' ) ) {
	return;
}

class WC_Product_Converge_Subscription_Variation extends WC_Product_Variation {

	public function __construct( $variation, $args = array() ) {
		$this->extra_data['wgc_plan_price']                             = null;
		$this->extra_data['wgc_plan_billing_frequency']                 = null;
		$this->extra_data['wgc_plan_billing_ending']                    = null;
		$this->extra_data['wgc_plan_introductory_rate']                 = null;
		$this->extra_data['wgc_plan_introductory_rate_amount']          = null;
		$this->extra_data['wgc_plan_introductory_rate_billing_periods'] = null;
		$this->extra_data['wgc_plan_billing_frequency_count']           = null;
		$this->extra_data['wgc_plan_ending_billing_periods']            = null;
		$this->extra_data['wgc_plan_id']                                = null;
		$this->data['parent'] = null;

		parent::__construct( $variation );
	}

	public static function init() {
		add_filter( 'woocommerce_product_class', __CLASS__ . '::get_classname', 10, 4 );
		add_filter( 'woocommerce_data_stores', __CLASS__ . '::add_data_store' );
	}

	public function __get( $key ) {
		return $this->get_prop( $key );
	}

	public function __set( $key, $value ) {
		$this->set_prop( $key, $value );
	}

	public function get_type() {
		return WGC_SUBSCRIPTION_VARIATION_NAME;
	}

	public function is_type( $type ) {
		return $type === 'variation' || $this->get_type() === $type || ( is_array( $type ) && in_array( $this->get_type(), $type ) );
	}

	public function get_price_html( $deprecated = '' ) {
		return  wgc_get_product_price_html( $this, parent::get_price_html( $deprecated ) );
	}
	
	public function get_price( $context = 'view' ) {

		$rate_billing_periods     = $this->get_wgc_plan_introductory_rate_billing_periods();
		$introductory_rate_amount = $this->get_wgc_plan_introductory_rate_amount();

		if ( $this->has_plan_introductory_rate() && (float) $introductory_rate_amount >= 0 && (float) $rate_billing_periods > 0 ) {
			$price = $introductory_rate_amount;
		} else {
			$price = $this->get_wgc_plan_price();
		}

		return $price;
	}

	public static function get_classname( $classname, $product_type, $post_type, $product_id ) {
		if ( $post_type === 'product_variation' ) {
			$post  = get_post( $product_id );
			$terms = get_the_terms( $post->post_parent, 'product_type' );

			$parent_product_type = ! empty( $terms ) && isset( current( $terms )->slug ) ? current( $terms )->slug : '';

			if ( $parent_product_type === WGC_VARIABLE_SUBSCRIPTION_NAME ) {
				$classname = __CLASS__;
			}
		}
		return $classname;
	}

	public static function add_data_store( $stores ) {
		$stores['product-converge-subscription-variation'] = 'WC_Product_Variation_Data_Store_CPT';
		return $stores;
	}

	public function get_wgc_plan_price() {
		return $this->get_prop( 'wgc_plan_price' );
	}

	public function set_wgc_plan_price( $wgc_plan_price ) {
		$this->set_prop( 'wgc_plan_price', $wgc_plan_price );
	}

	public function set_wgc_plan_billing_frequency( $wgc_plan_billing_frequency ) {
		$this->set_prop( 'wgc_plan_billing_frequency', $wgc_plan_billing_frequency );
	}

	public function set_wgc_plan_billing_ending( $wgc_plan_billing_ending ) {
		$this->set_prop( 'wgc_plan_billing_ending', $wgc_plan_billing_ending );
	}

	public function set_wgc_plan_introductory_rate( $wgc_plan_introductory_rate ) {
		$this->set_prop( 'wgc_plan_introductory_rate', $wgc_plan_introductory_rate );
	}

	public function set_wgc_plan_introductory_rate_amount( $wgc_plan_introductory_rate_amount ) {
		$this->set_prop( 'wgc_plan_introductory_rate_amount', $wgc_plan_introductory_rate_amount );
	}

	public function set_wgc_plan_introductory_rate_billing_periods( $wgc_plan_introductory_rate_billing_periods ) {
		$this->set_prop( 'wgc_plan_introductory_rate_billing_periods', $wgc_plan_introductory_rate_billing_periods );
	}

	public function set_wgc_plan_billing_frequency_count( $wgc_plan_billing_frequency_count ) {
		$this->set_prop( 'wgc_plan_billing_frequency_count', $wgc_plan_billing_frequency_count );
	}

	public function set_wgc_plan_ending_billing_periods( $wgc_plan_ending_billing_periods ) {
		$this->set_prop( 'wgc_plan_ending_billing_periods', $wgc_plan_ending_billing_periods );
	}

	public function set_wgc_plan_id( $wgc_plan_id ) {
		$this->set_prop( 'wgc_plan_id', $wgc_plan_id );
	}

	public function get_wgc_plan_billing_frequency() {
		return $this->get_prop( 'wgc_plan_billing_frequency' );
	}

	public function get_wgc_plan_billing_ending() {
		return $this->get_prop( 'wgc_plan_billing_ending' );
	}

	public function get_wgc_plan_introductory_rate() {
		return $this->get_prop( 'wgc_plan_introductory_rate' );
	}

	public function get_wgc_plan_introductory_rate_amount() {
		return $this->get_prop( 'wgc_plan_introductory_rate_amount' );
	}

	public function get_wgc_plan_introductory_rate_billing_periods() {
		return $this->get_prop( 'wgc_plan_introductory_rate_billing_periods' );
	}

	public function get_wgc_plan_billing_frequency_count() {
		return $this->get_prop( 'wgc_plan_billing_frequency_count' );
	}

	public function get_wgc_plan_ending_billing_periods() {
		return $this->get_prop( 'wgc_plan_ending_billing_periods' );
	}

	public function get_wgc_plan_id() {
		return $this->get_prop( 'wgc_plan_id' );
	}

	public function has_plan_introductory_rate() {
		return "yes" === $this->get_wgc_plan_introductory_rate();
	}
}
WC_Product_Converge_Subscription_Variation::init();
