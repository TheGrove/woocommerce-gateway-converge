<?php

namespace Elavon\Converge2\Operation;

use Elavon\Converge2\DataObject\Resource\Endpoint;
use Elavon\Converge2\Request\AbstractRequest;
use Elavon\Converge2\Request\GenericGetRequest;
use Elavon\Converge2\Response\ProcessorAccountResponse;
use Elavon\Converge2\Response\ResponseInterface;

/**
 * @method sendAndMakeResponse(AbstractRequest $request)
 * @method castResponseAs($class, ResponseInterface $response)
 */
trait ProcessorAccountOperationTrait
{
    /**
     * @param $id
     * @return ProcessorAccountResponse
     */
    public function getProcessorAccount($id)
    {
        $request = new GenericGetRequest(Endpoint::PROCESSOR_ACCOUNT, $id);
        $response = $this->sendAndMakeResponse($request);

        return $this->castResponseAs(ProcessorAccountResponse::class, $response);
    }
}
