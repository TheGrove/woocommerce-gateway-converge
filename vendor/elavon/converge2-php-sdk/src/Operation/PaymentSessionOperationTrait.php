<?php

namespace Elavon\Converge2\Operation;

use Elavon\Converge2\DataObject\Resource\Endpoint;
use Elavon\Converge2\Request\AbstractRequest;
use Elavon\Converge2\Request\GenericCreateRequest;
use Elavon\Converge2\Request\GenericGetRequest;
use Elavon\Converge2\Request\GenericUpdateRequest;
use Elavon\Converge2\Response\PaymentSessionResponse;
use Elavon\Converge2\Response\ResponseInterface;

/**
 * @method sendAndMakeResponse(AbstractRequest $request)
 * @method castResponseAs($class, ResponseInterface $response)
 */
trait PaymentSessionOperationTrait
{
    /**
     * @param $data
     * @return PaymentSessionResponse
     */
    public function createPaymentSession($data)
    {
        $request = new GenericCreateRequest(Endpoint::PAYMENT_SESSION, $data);
        $response = $this->sendAndMakeResponse($request);

        return $this->castResponseAs(PaymentSessionResponse::class, $response);
    }

    /**
     * @param $id
     * @return PaymentSessionResponse
     */
    public function getPaymentSession($id)
    {
        $request = new GenericGetRequest(Endpoint::PAYMENT_SESSION, $id);
        $response = $this->sendAndMakeResponse($request);

        return $this->castResponseAs(PaymentSessionResponse::class, $response);
    }

    /**
     * @param $id
     * @param $data
     * @return PaymentSessionResponse
     */
    public function updatePaymentSession($id, $data)
    {
        $request = new GenericUpdateRequest(Endpoint::PAYMENT_SESSION, $id, $data);
        $response = $this->sendAndMakeResponse($request);

        return $this->castResponseAs(PaymentSessionResponse::class, $response);
    }
}
