<?php

use Elavon\Converge2\Request\Payload\Validation\Constraint\AbstractConstraint;
use Elavon\Converge2\Request\Payload\Validation\Constraint\MaxLength;
use Elavon\Converge2\Request\Payload\Validation\Constraint\Required;
use Elavon\Converge2\Request\Payload\Validation\Constraint\Violation\Violation;
use Elavon\Converge2\Request\Payload\Validation\DataValidator;
use Elavon\Converge2\Schema\Converge2Schema;

class WC_Plan_Validator extends DataValidator {
	public function __construct( $values ) {
		parent::__construct();

		$this->setViolationRenderer( new WC_Subscription_Validation_Message( false ) );

		$converge_schema = Converge2Schema::getInstance();

		$required_constraint          = new Required();
		$common_max_length_constraint = new MaxLength( $converge_schema->getCommonMaxLength() );

		$field = 'wgc_plan_price';
		$this->addConstraint( $required_constraint, $field );
		$this->addConstraint( $common_max_length_constraint, $field );

		if ( $values['wgc_plan_introductory_rate'] ) {
			$field = 'wgc_plan_introductory_rate_amount';
			$this->addConstraint( $required_constraint, $field );

			$field = 'wgc_plan_introductory_rate_billing_periods';
			$this->addConstraint( $required_constraint, $field );
		}

		if ( $values['wgc_plan_billing_ending'] == 'billing_periods' ) {
			$field = 'wgc_plan_ending_billing_periods';
			$this->addConstraint( $required_constraint, $field );

			if ( $values['wgc_plan_introductory_rate'] ) {
				$int = new IntroductoryRateBillingPeriods( $values['wgc_plan_introductory_rate_billing_periods'] );
				$this->addConstraint( $int, $field );
			}
		}
	}
}

class IntroductoryRateBillingPeriods extends AbstractConstraint {
	const ID = 'IntroductoryRateBillingPeriods';

	protected $id = self::ID;
	protected $errorMessageTemplate = 'The number of billing periods after a subscription ends must exceed the number of introductory rate billing periods.';
	protected $introductoryRateBillingPeriods;

	public function __construct( $introductoryRateBillingPeriods, $errorMessageTemplate = '' ) {
		$this->introductoryRateBillingPeriods = isset( $introductoryRateBillingPeriods ) ? (int) $introductoryRateBillingPeriods : null;
		parent::__construct( $errorMessageTemplate );
	}

	public function assert( $endingBillingPeriods ) {
		$violations = array();

		if ( $endingBillingPeriods < $this->introductoryRateBillingPeriods ) {
			$violations[] = new Violation(
				$this->id,
				$this->errorMessageTemplate,
				$endingBillingPeriods
			);
		}

		return $violations;
	}
}