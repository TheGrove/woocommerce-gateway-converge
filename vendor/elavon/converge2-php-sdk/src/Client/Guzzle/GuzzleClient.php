<?php

namespace Elavon\Converge2\Client\Guzzle;

use Elavon\Converge2\Client\ClientConfigInterface;
use Elavon\Converge2\Client\ClientInterface;
use Elavon\Converge2\Client\ClientTrait;
use Elavon\Converge2\Exception\InvalidBodyException;
use Elavon\Converge2\Request\RequestInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;

class GuzzleClient extends \GuzzleHttp\Client implements ClientInterface
{
    use ClientTrait;

    public function __construct(ClientConfigInterface $c2_config, array $client_config = array())
    {
        // Force Guzzle to throw exceptions on HTTP protocol errors (4xx and 5xx response codes).
        $client_config['http_errors'] = true;

        $this->initClient(new GuzzleResponseFactory(), $c2_config, $client_config);

        parent::__construct($this->clientConfig);
    }

    public function sendRequest(RequestInterface $request, array $options = array())
    {
        return new PsrToRawResponseAdapter(
            parent::send(new ConvergeToPsrRequestAdapter($request), $options)
        );
    }

    public function sendRequestAndMakeResponse(RequestInterface $request, array $options = array())
    {
        try {
            $raw_response = $this->sendWithBasicAuth($request, $options);
            $response = $this->responseFactory->create20xResponse($raw_response);
        } catch (ClientException $e) {
            $response = $this->responseFactory->createClientExceptionResponse($e);
        } catch (GuzzleException $e) {
            $response = $this->responseFactory->createGuzzleExceptionResponse($e);
        } catch (InvalidBodyException $e) {
            $response = $this->responseFactory->createInvalidBodyExceptionResponse($e);
        }

        return $response;
    }
}

