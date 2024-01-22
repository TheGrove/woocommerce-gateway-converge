<?php

namespace Elavon\Converge2\Operation;

use Elavon\Converge2\DataObject\Resource\Endpoint;
use Elavon\Converge2\Request\AbstractRequest;
use Elavon\Converge2\Request\GenericGetRequest;
use Elavon\Converge2\Response\MerchantResponse;
use Elavon\Converge2\Response\ResponseInterface;

/**
 * @method sendAndMakeResponse(AbstractRequest $request)
 * @method castResponseAs($class, ResponseInterface $response)
 */
trait MerchantOperationTrait
{
    /**
     * @param $id
     * @return MerchantResponse
     */
    public function getMerchant($id)
    {
        $request = new GenericGetRequest(Endpoint::MERCHANT, $id);
        $response = $this->sendAndMakeResponse($request);

        return $this->castResponseAs(MerchantResponse::class, $response);
    }
}
