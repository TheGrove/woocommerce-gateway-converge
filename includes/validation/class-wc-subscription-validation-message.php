<?php

use Elavon\Converge2\Request\Payload\Validation\Constraint\Violation\ViolationInterface;

class WC_Subscription_Validation_Message extends WC_Validation_Message {

	public function toString( ViolationInterface $violation ) {
		$constraint = $violation->getConstraintId();

		if ( $constraint == IntroductoryRateBillingPeriods::ID ) {
			return $this->error_messages[ $constraint ];
		}

		return parent::toString( $violation );
	}

	protected function init_error_messages() {
		parent::init_error_messages();
		$this->error_messages[ IntroductoryRateBillingPeriods::ID ] = __( 'The number of billing periods after a subscription ends must exceed the number of introductory rate billing periods.',
			'elavon-converge-gateway' );
	}

	protected function setFieldLabels() {
		$this->field_labels = array(
			'wgc_plan_price'                             => __( 'Subscription Price', 'elavon-converge-gateway' ),
			'wgc_plan_introductory_rate_amount'          => __( 'Introductory Rate Bill Amount',
				'elavon-converge-gateway' ),
			'wgc_plan_introductory_rate_billing_periods' => __( 'Introductory Rate Billing Periods',
				'elavon-converge-gateway' ),
			'wgc_plan_ending_billing_periods'            => __( 'Ending Billing Periods', 'elavon-converge-gateway' ),
		);
	}
}