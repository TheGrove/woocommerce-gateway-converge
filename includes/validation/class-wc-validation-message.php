<?php

use Elavon\Converge2\Request\Payload\Validation\Constraint\Violation\ViolationRendererInterface;
use Elavon\Converge2\Request\Payload\Validation\Constraint\Violation\ViolationInterface;
use Elavon\Converge2\Request\Payload\Validation\Constraint\MaxLength;
use Elavon\Converge2\Request\Payload\Validation\Constraint\BasicSafeString;
use Elavon\Converge2\Request\Payload\Validation\Constraint\PhoneSafeString;
use Elavon\Converge2\Request\Payload\Validation\Constraint\Required;

class WC_Validation_Message implements ViolationRendererInterface {

	protected $error_messages = array();
	protected $field_labels = array();
	protected $fix_labels;

	public function __construct( $fix_labels = true ) {
		$this->fix_labels = $fix_labels;
		$this->init_error_messages();
		$this->setFieldLabels();
	}

	protected function init_error_messages() {
		$this->error_messages = array(
			Required::ID        =>
			/* translators: %1$s: already translated field name */
				__( 'The field %1$s is required.', 'elavon-converge-gateway' ),
			MaxLength::ID       =>
			/* translators: %1$s: already translated field name, %2$s: a number */
				__( 'The field %1$s cannot be longer than %2$s characters.', 'elavon-converge-gateway' ),
			BasicSafeString::ID =>
			/* translators: %1$s: already translated field name, %2$s: a list of special characters */
				__( 'The field %1$s does not allow the following special characters: %2$s', 'elavon-converge-gateway' ),
			PhoneSafeString::ID =>
			/* translators: %1$s: already translated field name, %2$s: a list of special characters */
				__( 'The field %1$s allows only these special characters: %2$s including space.',
					'elavon-converge-gateway' ),
		);
	}

	protected function setFieldLabels() {
		$this->field_labels = array(
			'billing'                                => __( 'Billing Address', 'elavon-converge-gateway' ),
			'shipping'                               => __( 'Shipping Address', 'elavon-converge-gateway' ),
			'full_name'                              => __( 'Full name (First name and Last name)',
				'elavon-converge-gateway' ),
			'first_name'                             => __( 'First name', 'elavon-converge-gateway' ),
			'last_name'                              => __( 'Last name', 'elavon-converge-gateway' ),
			'company'                                => __( 'Company name', 'elavon-converge-gateway' ),
			'address_1'                              => __( 'Street address 1', 'elavon-converge-gateway' ),
			'address_2'                              => __( 'Street address 2', 'elavon-converge-gateway' ),
			'city'                                   => __( 'Town / City', 'elavon-converge-gateway' ),
			'state'                                  => __( 'State / County', 'elavon-converge-gateway' ),
			'postcode'                               => __( 'Postcode / ZIP', 'elavon-converge-gateway' ),
			WGC_KEY_PHONE                            => __( 'Phone', 'elavon-converge-gateway' ),
			WGC_KEY_TITLE                            => __( 'Title', 'elavon-converge-gateway' ),
			WGC_KEY_SAVE_FOR_LATER_USE_MESSAGE       => __( 'Save For Later Use Message', 'elavon-converge-gateway' ),
			WGC_KEY_NAME                             => __( 'Name', 'elavon-converge-gateway' ),
			WGC_KEY_URL                              => __( 'URL', 'elavon-converge-gateway' ),
			WGC_KEY_PROCESSOR_ACCOUNT_ID             => __( 'Processor Account Id', 'elavon-converge-gateway' ),
			WGC_KEY_SUBSCRIPTIONS_DISCLOSURE_MESSAGE => __( 'Disclosure Message for Subscriptions',
				'elavon-converge-gateway' ),
			'order_comments'                         => __( 'Order notes', 'elavon-converge-gateway' ),
		);
	}

	public function toString( ViolationInterface $violation ) {
		$field = $violation->getField();
		$label = '';

		if ( $this->fix_labels ) {
			if ( strpos( $field, 'billing_' ) !== false ) {
				$field = str_replace( 'billing_', '', $field );
				$label = ' - ' . $this->field_labels['billing'];
			} elseif ( strpos( $field, 'shipping_' ) !== false ) {
				$field = str_replace( 'shipping_', '', $field );
				$label = ' - ' . $this->field_labels['shipping'];
			}
		}

		if ( isset( $this->field_labels[ $field ] ) ) {
			$label = $this->field_labels[ $field ] . $label;
		}

		$label = $this->makeBold( $label );

		$constraint = $violation->getConstraintId();

		switch ( $constraint ) {
			case Required::ID:
				$message = sprintf(
					$this->error_messages[ $constraint ],
					$label
				);
				break;
			case MaxLength::ID:
				$message = sprintf(
					$this->error_messages[ $constraint ],
					$label,
					$this->makeBold( $violation->getConstraintParameter() )
				);
				break;
			case BasicSafeString::ID:
				$message = sprintf(
					$this->error_messages[ $constraint ],
					$label,
					$this->makeBold( htmlspecialchars( BasicSafeString::FORBIDDEN ) )
				);
				break;
			case PhoneSafeString::ID:
				$message = sprintf(
					$this->error_messages[ $constraint ],
					$label,
					$this->makeBold( htmlspecialchars( PhoneSafeString::ALLOWED ) )
				);
				break;
			default:
				$message = $violation->getFormattedMessage();
		}

		return $message;
	}

	protected function makeBold( $text ) {
		if ( $text ) {
			return "<strong>$text</strong>";
		} else {
			return $text;
		}
	}
}