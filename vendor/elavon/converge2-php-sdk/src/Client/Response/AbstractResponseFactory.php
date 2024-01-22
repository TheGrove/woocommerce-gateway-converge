<?php

namespace Elavon\Converge2\Client\Response;

use Elavon\Converge2\Exception\InvalidBodyException;
use Elavon\Converge2\Response\Response;

abstract class AbstractResponseFactory implements ResponseFactoryInterface
{
    public function create20xResponse(RawResponseInterface $raw_response)
    {
        $response = new Response();
        $response->setSuccess(true);
        $response->setRawResponse($raw_response);
        return $response;
    }

    public function createExceptionResponse(\Exception $e)
    {
        $response = new Response();
        $response->setSuccess(false);
        $response->setShortErrorMessage('Unexpected error.');
        $response->setRawErrorMessage($e->getMessage());
        $response->setException($e);
        return $response;
    }

    public function createInvalidBodyExceptionResponse(InvalidBodyException $e)
    {
        $response = new Response();
        $response->setSuccess(false);
        $response->setShortErrorMessage($e->getPrevious()->getMessage());
        $response->setRawErrorMessage($e->getMessage());
        $response->setException($e);
        return $response;
    }
}
