<?php

namespace Elavon\Converge2\Operation;

use Elavon\Converge2\DataObject\Resource\Endpoint;
use Elavon\Converge2\Request\AbstractRequest;
use Elavon\Converge2\Request\GenericGetRequest;
use Elavon\Converge2\Request\GenericListRequest;
use Elavon\Converge2\Response\BatchResponse;
use Elavon\Converge2\Response\BatchPagedListResponse;
use Elavon\Converge2\Response\ResponseInterface;

/**
 * @method sendAndMakeResponse(AbstractRequest $request)
 * @method castResponseAs($class, ResponseInterface $response)
 */
trait BatchOperationTrait
{
    /**
     * @param $id
     * @return BatchResponse
     */
    public function getBatch($id)
    {
        $request = new GenericGetRequest(Endpoint::BATCH, $id);
        $response = $this->sendAndMakeResponse($request);

        return $this->castResponseAs(BatchResponse::class, $response);
    }

    /**
     * @param string $query_str
     * @return BatchPagedListResponse
     */
    public function getBatchList($query_str = '')
    {
        $request = new GenericListRequest(Endpoint::BATCH, $query_str);
        $response = $this->sendAndMakeResponse($request);

        return $this->castResponseAs(BatchPagedListResponse::class, $response);
    }
}
