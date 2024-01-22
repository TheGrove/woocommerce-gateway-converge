<?php

use Elavon\Converge2\DataObject\Resource\StoredCardInterface;

if ( ! defined( 'ABSPATH' ) ) {
	exit (); // Exit if accessed directly.
}

class WC_Payment_Token_Gateway_Converge_StoredCard extends WC_Payment_Token {
	/**
	 * @var string
	 */
	protected $type = WGC_PAYMENT_TOKEN_TYPE;

	/** @var \Elavon\Converge2\Util\Encryption */
	protected $encryption;

	/**
	 * @var array
	 */
	protected $extra_data = array(
		'last4'        => '',
		'expiry_year'  => '',
		'expiry_month' => '',
		'card_scheme'  => '',
	);

	public function __construct( $token = '' ) {
		$this->encryption = new \Elavon\Converge2\Util\Encryption( wp_salt() );
		parent::__construct( $token );
	}

	public function get_expiry_date() {
		$expiry_date = new DateTime( $this->get_expiry_year() . '-' . $this->get_expiry_month() );
		$expiry_date->modify('first day of next month');
		$expiry_date->modify('-1 second');
		return $expiry_date;
	}

	public function is_expired() {
		$now = new DateTime( 'now', new \DateTimeZone( 'UTC' ));
		$expiry_date = $this->get_expiry_date() ;

		if ( $now  > $expiry_date ) {
			return true;
		} else {
			return false;
		}
	}

	public function get_display_name( $deprecated = '' ) {

		if ( $this->is_expired() ) {
			/* translators: %1$s: credit card brand, %2$s: last 4 digits, %3$s: expiry month 4: expiry year */
			$display_name_format = __( '%1$s ending in %2$s <span class="red">(expired %3$s/%4$s)</span>', 'elavon-converge-gateway' );
		} else {
			/* translators: %1$s: credit card brand, %2$s: last 4 digits, %3$s: expiry month 4: expiry year */
			$display_name_format = __( '%1$s ending in %2$s (expires %3$s/%4$s)', 'elavon-converge-gateway' );
		}

		return sprintf(
			$display_name_format,
			$this->get_card_scheme(),
			$this->get_last4(),
			$this->get_expiry_month(),
			substr( $this->get_expiry_year(), 2 )
		);
	}

	protected function get_hook_prefix() {
		return 'woocommerce_payment_token_cc_get_';
	}

	public function validate() {
		if ( false === parent::validate() ) {
			return false;
		}

		if ( ! $this->get_last4( 'edit' ) ) {
			return false;
		}

		if ( ! $this->get_expiry_year( 'edit' ) ) {
			return false;
		}

		if ( ! $this->get_expiry_month( 'edit' ) ) {
			return false;
		}

		if ( ! $this->get_card_scheme( 'edit' ) ) {
			return false;
		}

		if ( 4 !== strlen( $this->get_expiry_year( 'edit' ) ) ) {
			return false;
		}

		if ( 2 !== strlen( $this->get_expiry_month( 'edit' ) ) ) {
			return false;
		}

		return true;
	}

	public function get_card_scheme( $context = 'view' ) {
		return $this->get_prop( 'card_scheme', $context );
	}

	public function set_card_scheme( $brand ) {
		$this->set_prop( 'card_scheme', $brand );
	}

	public function get_expiry_year( $context = 'view' ) {
		return $this->get_prop( 'expiry_year', $context );
	}

	public function set_expiry_year( $year ) {
		$this->set_prop( 'expiry_year', $year );
	}

	public function get_expiry_month( $context = 'view' ) {
		return $this->get_prop( 'expiry_month', $context );
	}

	public function set_expiry_month( $month ) {
		$this->set_prop( 'expiry_month', str_pad( $month, 2, '0', STR_PAD_LEFT ) );
	}

	public function get_last4( $context = 'view' ) {
		return $this->get_prop( 'last4', $context );
	}

	public function set_last4( $last4 ) {
		$this->set_prop( 'last4', $last4 );
	}

	public function get_token( $context = 'view' ) {
		$token = parent::get_token( $context );

		return
			wgc_get_payment_name() == $context
				? $this->encryption->decryptCredential( $token )
				: $token;
	}

	public function init_from_stored_card( StoredCardInterface $stored_card ) {
		$this->set_token( $this->encryption->encryptCredential( $stored_card->getId() ) );
		$card = $stored_card->getCard();
		$this->set_last4( $card->getLast4() );
		$this->set_expiry_month( $card->getExpirationMonth() );
		$this->set_expiry_year( $card->getExpirationYear() );
		$this->set_card_scheme( $card->getScheme()->getValue() );
	}

	public function delete( $force_delete = false ) {
		$stored_card         = $this->get_token( wgc_get_payment_name() );
		$deleted_on_converge = wgc_get_gateway()->delete_stored_card( $stored_card );
		
		if ( $deleted_on_converge ) {
			$parent_result = parent::delete( $force_delete );
			if ( $this->get_is_default() ) {
				$tokens = WC_Payment_Tokens::get_customer_tokens( get_current_user_id(), $this->get_gateway_id() );
				$token  = reset( $tokens );
				if ( $token ) {
					$token->set_default( true );
					$token->save();
				}
			}

			return $parent_result;
		} else {
			wc_add_notice(
				__( 'Payment method cannot be deleted. Reason: there is a subscription associated with this payment method or there is a connection issue.', 'elavon-converge-gateway' ),
				'error'
			);
			wp_redirect( wc_get_account_endpoint_url( 'payment-methods' ) );
			exit();
		}
	}

}
