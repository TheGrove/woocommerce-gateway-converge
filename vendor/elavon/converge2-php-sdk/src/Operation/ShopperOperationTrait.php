<?php

namespace Elavon\Converge2\Operation;

use Elavon\Converge2\DataObject\Resource\Endpoint;
use Elavon\Converge2\Request\AbstractRequest;
use Elavon\Converge2\Request\GenericCreateRequest;
use Elavon\Converge2\Request\GenericDeleteRequest;
use Elavon\Converge2\Request\GenericGetRequest;
use Elavon\Converge2\Request\GenericListRequest;
use Elavon\Converge2\Request\GenericUpdateRequest;
use Elavon\Converge2\Request\ListShopperStoredCardRequest;
use Elavon\Converge2\Response\ShopperPagedListResponse;
use Elavon\Converge2\Response\ShopperResponse;
use Elavon\Converge2\Response\StoredCardPagedListResponse;
use Elavon\Converge2\Response\ResponseInterface;

/**
 * @method sendAndMakeResponse(AbstractRequest $request)
 * @method castResponseAs($class, ResponseInterface $response)
 */
trait ShopperOperationTrait
{
    /**
     * @param $data
     * @return ShopperResponse
     */
    public function createShopper($data)
    {
        $request = new GenericCreateRequest(Endpoint::SHOPPER, $data);
        $response = $this->sendAndMakeResponse($request);

        return $this->castResponseAs(ShopperResponse::class, $response);
    }

    /**
     * @param $id
     * @return ShopperResponse
     */
    public function getShopper($id)
    {
        $request = new GenericGetRequest(Endpoint::SHOPPER, $id);
        $response = $this->sendAndMakeResponse($request);

        return $this->castResponseAs(ShopperResponse::class, $response);
    }

    /**
     * @param string $query_str
     * @return ShopperPagedListResponse
     */
    public function getShopperList($query_str = '')
    {
        $request = new GenericListRequest(Endpoint::SHOPPER, $query_str);
        $response = $this->sendAndMakeResponse($request);

        return $this->castResponseAs(ShopperPagedListResponse::class, $response);
    }

    /**
     * @param string $shopper_id
     * @param string $query_str
     * @return StoredCardPagedListResponse
     */
    public function getShopperStoredCardList($shopper_id, $query_str = '')
    {
        $request = new ListShopperStoredCardRequest($shopper_id, $query_str);
        $response = $this->sendAndMakeResponse($request);

        return $this->castResponseAs(StoredCardPagedListResponse::class, $response);
    }

    /**
     * @param $id
     * @param $data
     * @return ShopperResponse
     */
    public function updateShopper($id, $data)
    {
        $request = new GenericUpdateRequest(Endpoint::SHOPPER, $id, $data);
        $response = $this->sendAndMakeResponse($request);

        return $this->castResponseAs(ShopperResponse::class, $response);
    }

    /**
     * @param $id
     * @return ShopperResponse
     */
    public function deleteShopper($id)
    {
        $request = new GenericDeleteRequest(Endpoint::SHOPPER, $id);
        $response = $this->sendAndMakeResponse($request);

        return $this->castResponseAs(ShopperResponse::class, $response);
    }
}
