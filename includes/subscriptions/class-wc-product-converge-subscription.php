<?php
defined( 'ABSPATH' ) || exit();

if ( ! class_exists( 'WC_Product_Simple' ) ) {
	return;
}

class WC_Product_Converge_Subscription extends WC_Product_Simple {

	public function __construct( $product ) {
		$this->extra_data['wgc_plan_price']                             = null;
		$this->extra_data['wgc_plan_billing_frequency']                 = null;
		$this->extra_data['wgc_plan_billing_ending']                    = null;
		$this->extra_data['wgc_plan_introductory_rate']                 = null;
		$this->extra_data['wgc_plan_introductory_rate_amount']          = null;
		$this->extra_data['wgc_plan_introductory_rate_billing_periods'] = null;
		$this->extra_data['wgc_plan_billing_frequency_count']           = null;
		$this->extra_data['wgc_plan_ending_billing_periods']            = null;
		$this->extra_data['wgc_plan_id']                                = null;

		parent::__construct( $product );
		$this->product_type = WGC_SUBSCRIPTION_NAME;
	}

	public function get_type() {
		return WGC_SUBSCRIPTION_NAME;
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

	public function get_wgc_plan_price() {
		return $this->get_prop( 'wgc_plan_price' );
	}

	public function get_price_html( $deprecated = '' ) {
		return wgc_get_product_price_html( $this, parent::get_price_html( $deprecated ) );
	}

	public function is_on_sale( $context = 'view' ) {
		return false;
	}

	public function __get( $key ) {
		return $this->get_prop( $key );
	}

	public function __set( $key, $value ) {
		$this->set_prop( $key, $value );
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
		return 'yes' === $this->get_wgc_plan_introductory_rate();
	}
}
