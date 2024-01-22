<?php

use Elavon\Converge2\Handler\ResponseHandlerInterface;
use Elavon\Converge2\Response\ResponseInterface;

class WC_Gateway_Converge_Response_Log_Handler implements ResponseHandlerInterface {

	/** @var string */
	protected $requestType;

	public function __construct( $requestType = '' ) {
		$this->withRequestType( $requestType );
	}

	/**
	 * @param string $requestType
	 *
	 * @return $this
	 */
	public function withRequestType( $requestType ) {
		$this->requestType = $requestType;

		return $this;
	}

	public function handle( ResponseInterface $response ) {
		wgc_log_converge_response( $response, $this->requestType );
	}
}
