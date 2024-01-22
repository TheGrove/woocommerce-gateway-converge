<?php

namespace Elavon\Converge2\Operation;

use Elavon\Converge2\DataObject\Resource\Endpoint;
use Elavon\Converge2\Request\AbstractRequest;
use Elavon\Converge2\Request\GenericCreateRequest;
use Elavon\Converge2\Request\GenericGetRequest;
use Elavon\Converge2\Request\GenericListRequest;
use Elavon\Converge2\Request\GenericUpdateRequest;
use Elavon\Converge2\Request\Payload\TransactionDataBuilder;
use Elavon\Converge2\Response\ResponseInterface;
use Elavon\Converge2\Response\TransactionPagedListResponse;
use Elavon\Converge2\Response\TransactionResponse;

/**
 * @method sendAndMakeResponse(AbstractRequest $request)
 * @method castResponseAs($class, ResponseInterface $response)
 */
trait TransactionOperationTrait
{
    protected function castAsTransactionResponse(ResponseInterface $response)
    {
        $response = new TransactionResponse($response);
        if ($response->hasFailures()) {
            $response->setSuccess(false);
            $response->setShortErrorMessage($response->getFirstFailure()->getDescription());
            $response->setRawErrorMessage($response->getFailuresAsJson());
        }

        return $response;
    }

    /**
     * @param $data
     * @return TransactionResponse
     */
    protected function createTransaction($data)
    {
        $request = new GenericCreateRequest(Endpoint::TRANSACTION, $data);
        $response = $this->sendAndMakeResponse($request);

        return $this->castAsTransactionResponse($response);
    }

    /**
     * @param $data
     * @return TransactionResponse
     */
    public function createSaleTransaction($data)
    {
        return $this->createTransaction($data);
    }

    /**
     * @param $id
     * @return TransactionResponse
     */
    public function getTransaction($id)
    {
        $request = new GenericGetRequest(Endpoint::TRANSACTION, $id);
        $response = $this->sendAndMakeResponse($request);

        return $this->castAsTransactionResponse($response);
    }

    /**
     * @param $data
     * @return TransactionResponse
     */
    public function createVoidTransaction($data)
    {
        return $this->createTransaction($data);
    }

    /**
     * @param $data
     * @return TransactionResponse
     */
    public function createRefundTransaction($data)
    {
        return $this->createTransaction($data);
    }

    /**
     * @param $id
     * @param $data
     * @return TransactionResponse
     */
    public function updateTransaction($id, $data)
    {
        $request = new GenericUpdateRequest(Endpoint::TRANSACTION, $id, $data);
        $response = $this->sendAndMakeResponse($request);

        return $this->castAsTransactionResponse($response);
    }

    /**
     * @param $id
     * @return TransactionResponse
     */
    public function captureTransaction($id)
    {
        $transaction_builder = new TransactionDataBuilder();
        $transaction_builder->setDoCapture(true);
        return $this->updateTransaction($id, $transaction_builder->getData());
    }

    /**
     * @param string $query_str
     * @return TransactionPagedListResponse
     */
    public function getTransactionList($query_str = '')
    {
        $request = new GenericListRequest(Endpoint::TRANSACTION, $query_str);
        $response = $this->sendAndMakeResponse($request);

        return $this->castResponseAs(TransactionPagedListResponse::class, $response);
    }
}
