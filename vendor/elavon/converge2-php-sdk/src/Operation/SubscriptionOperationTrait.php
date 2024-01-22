<?php

namespace Elavon\Converge2\Operation;

use Elavon\Converge2\DataObject\Resource\Endpoint;
use Elavon\Converge2\Request\AbstractRequest;
use Elavon\Converge2\Request\GenericCreateRequest;
use Elavon\Converge2\Request\GenericGetRequest;
use Elavon\Converge2\Request\GenericListRequest;
use Elavon\Converge2\Request\GenericUpdateRequest;
use Elavon\Converge2\Response\SubscriptionPagedListResponse;
use Elavon\Converge2\Response\SubscriptionResponse;
use Elavon\Converge2\Response\ResponseInterface;

/**
 * @method sendAndMakeResponse(AbstractRequest $request)
 * @method castResponseAs($class, ResponseInterface $response)
 */
trait SubscriptionOperationTrait
{
    /**
     * @param $data
     * @return SubscriptionResponse
     */
    public function createSubscription($data)
    {
        $request = new GenericCreateRequest(Endpoint::SUBSCRIPTION, $data);
        $response = $this->sendAndMakeResponse($request);

        return $this->castResponseAs(SubscriptionResponse::class, $response);
    }

    /**
     * @param $id
     * @return SubscriptionResponse
     */
    public function getSubscription($id)
    {
        $request = new GenericGetRequest(Endpoint::SUBSCRIPTION, $id);
        $response = $this->sendAndMakeResponse($request);

        return $this->castResponseAs(SubscriptionResponse::class, $response);
    }

    /**
     * @param string $query_str
     * @return SubscriptionPagedListResponse
     */
    public function getSubscriptionList($query_str = '')
    {
        $request = new GenericListRequest(Endpoint::SUBSCRIPTION, $query_str);
        $response = $this->sendAndMakeResponse($request);

        return $this->castResponseAs(SubscriptionPagedListResponse::class, $response);
    }

    /**
     * @param $id
     * @param $data
     * @return SubscriptionResponse
     */
    public function updateSubscription($id, $data)
    {
        $request = new GenericUpdateRequest(Endpoint::SUBSCRIPTION, $id, $data);
        $response = $this->sendAndMakeResponse($request);

        return $this->castResponseAs(SubscriptionResponse::class, $response);
    }
}
