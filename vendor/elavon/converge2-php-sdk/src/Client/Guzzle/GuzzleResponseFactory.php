<?php

namespace Elavon\Converge2\Client\Guzzle;

use Elavon\Converge2\Client\Response\AbstractResponseFactory;
use GuzzleHttp\Exception\ClientException;
use Elavon\Converge2\Response\Response;

class GuzzleResponseFactory extends AbstractResponseFactory
{
    public function createClientExceptionResponse(ClientException $e)
    {
        $response = new Response();
        $response->setSuccess(false);
        $response->setShortErrorMessage($e->getResponse()->getReasonPhrase());
        $response->setRawErrorMessage($e->getMessage());
        $response->setRawResponse(new PsrToRawResponseAdapter($e->getResponse()));
        $response->setException($e);
        return $response;
    }

    public function createGuzzleExceptionResponse(\Exception $e)
    {
        $response = new Response();
        $response->setSuccess(false);
        $response->setShortErrorMessage('Unexpected client error.');
        $response->setRawErrorMessage($e->getMessage());
        $response->setException($e);
        return $response;
    }
}
