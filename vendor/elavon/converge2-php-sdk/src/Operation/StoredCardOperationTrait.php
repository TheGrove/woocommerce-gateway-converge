<?php

namespace Elavon\Converge2\Operation;

use Elavon\Converge2\DataObject\Resource\Endpoint;
use Elavon\Converge2\Request\AbstractRequest;
use Elavon\Converge2\Request\GenericCreateRequest;
use Elavon\Converge2\Request\GenericDeleteRequest;
use Elavon\Converge2\Request\GenericGetRequest;
use Elavon\Converge2\Request\GenericUpdateRequest;
use Elavon\Converge2\Response\StoredCardResponse;
use Elavon\Converge2\Response\ResponseInterface;

/**
 * @method sendAndMakeResponse(AbstractRequest $request)
 * @method castResponseAs($class, ResponseInterface $response)
 */
trait StoredCardOperationTrait
{
    /**
     * @param $data
     * @return StoredCardResponse
     */
    public function createStoredCard($data)
    {
        $request = new GenericCreateRequest(Endpoint::STORED_CARD, $data);
        $response = $this->sendAndMakeResponse($request);

        return $this->castResponseAs(StoredCardResponse::class, $response);
    }

    /**
     * @param $id
     * @return StoredCardResponse
     */
    public function getStoredCard($id)
    {
        $request = new GenericGetRequest(Endpoint::STORED_CARD, $id);
        $response = $this->sendAndMakeResponse($request);

        return $this->castResponseAs(StoredCardResponse::class, $response);
    }

    /**
     * @param $id
     * @param $data
     * @return StoredCardResponse
     */
    public function updateStoredCard($id, $data)
    {
        $request = new GenericUpdateRequest(Endpoint::STORED_CARD, $id, $data);
        $response = $this->sendAndMakeResponse($request);

        return $this->castResponseAs(StoredCardResponse::class, $response);
    }

    /**
     * @param $id
     * @return StoredCardResponse
     */
    public function deleteStoredCard($id)
    {
        $request = new GenericDeleteRequest(Endpoint::STORED_CARD, $id);
        $response = $this->sendAndMakeResponse($request);

        return $this->castResponseAs(StoredCardResponse::class, $response);
    }
}
