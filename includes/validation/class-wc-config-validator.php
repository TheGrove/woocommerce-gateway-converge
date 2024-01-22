<?php

use Elavon\Converge2\Request\Payload\Validation\DataValidator;
use Elavon\Converge2\Request\Payload\Validation\Constraint\MaxLength;
use Elavon\Converge2\Request\Payload\Validation\Constraint\BasicSafeString;
use Elavon\Converge2\Request\Payload\Validation\Constraint\PhoneSafeString;
use Elavon\Converge2\Request\Payload\Validation\Constraint\Required;
use Elavon\Converge2\Schema\Converge2Schema;

class WC_Config_Validator extends DataValidator {
	public function __construct() {
		parent::__construct();

		$this->setViolationRenderer( new WC_Validation_Message() );

		$converge_schema = Converge2Schema::getInstance();

		$required_constraint = new Required();

		$field = WGC_KEY_TITLE;
		$this->maxLength( WGC_KEY_TITLE_MAXLENGTH, $field );

		$field = WGC_KEY_SAVE_FOR_LATER_USE_MESSAGE;
		$this->addConstraint( $required_constraint, $field );
		$this->maxLength( WGC_KEY_SAVE_FOR_LATER_USE_MESSAGE_MAXLENGTH, $field );

		$field = WGC_KEY_SUBSCRIPTIONS_DISCLOSURE_MESSAGE;
		$this->maxLength( WGC_KEY_SUBSCRIPTIONS_DISCLOSURE_MESSAGE_MAXLENGTH, $field );

		$field = WGC_KEY_NAME;
		$this->maxLength( $converge_schema->getShopperStatementNameMaxLength(), $field );
		$this->basicSafeString( $field );

		$field = WGC_KEY_PHONE;
		$this->maxLength( $converge_schema->getShopperStatementPhoneMaxLength(), $field );
		$this->phoneSafeString( $field );

		$field = WGC_KEY_URL;
		$this->maxLength( $converge_schema->getShopperStatementUrlMaxLength(), $field );

		$field = WGC_KEY_PROCESSOR_ACCOUNT_ID;
		$this->addConstraint( $required_constraint, $field );
	}
}
