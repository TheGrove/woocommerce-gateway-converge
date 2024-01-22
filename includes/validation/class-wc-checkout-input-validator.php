<?php

use Elavon\Converge2\Request\Payload\Validation\DataValidator;
use Elavon\Converge2\Request\Payload\Validation\Constraint\MaxLength;
use Elavon\Converge2\Request\Payload\Validation\Constraint\BasicSafeString;
use Elavon\Converge2\Request\Payload\Validation\Constraint\PhoneSafeString;
use Elavon\Converge2\Schema\Converge2Schema;

class WC_Checkout_Input_Validator extends DataValidator {
	public function __construct() {
		parent::__construct();

		$this->setViolationRenderer(new WC_Validation_Message());

		$converge_schema = Converge2Schema::getInstance();

		$common_max_length_constraint = new MaxLength($converge_schema->getCommonMaxLength());
		$basic_safe_string_constraint = new BasicSafeString();
		$phone_safe_string_constraint = new PhoneSafeString();

		foreach (array(
			'billing',
			'shipping',
		) as $group) {
			foreach (
				array(
					'first_name',
					'last_name',
				) as $field
			) {
				$field = $group . '_' . $field;
				$this->addConstraint( $basic_safe_string_constraint, $field );
			}

			$this->addConstraint( $common_max_length_constraint, $group . '_full_name' );

			foreach (
				array(
					'company',
					'address_1',
					'address_2',
					'city',
					'state',
					'postcode'
				) as $field
			) {
				$field = $group . '_' . $field;
				$this->addConstraint( $common_max_length_constraint, $field );
				$this->addConstraint( $basic_safe_string_constraint, $field );
			}

			foreach (
				array(
					'phone',
				) as $field
			) {
				$field = $group . '_' . $field;
				$this->addConstraint( $common_max_length_constraint, $field );
				$this->addConstraint( $phone_safe_string_constraint, $field );
			}
		}

		$field = 'order_comments';
		$this->maxLength($converge_schema->getShopperReferenceMaxLength(), $field);
	}
}
