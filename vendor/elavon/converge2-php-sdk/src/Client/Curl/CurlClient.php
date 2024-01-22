<?php

namespace Elavon\Converge2\Client\Curl;

use Elavon\Converge2\Client\ClientConfigInterface;
use Elavon\Converge2\Client\ClientInterface;
use Elavon\Converge2\Client\ClientTrait;
use Elavon\Converge2\Client\Curl\Exception\RequestException;
use Elavon\Converge2\Exception\InvalidBodyException;
use Elavon\Converge2\Request\RequestInterface;

/**
 * Converge2 CurlClient.
 */
class CurlClient implements ClientInterface
{
    use ClientTrait;

    public function __construct(ClientConfigInterface $c2_config, array $client_config = array())
    {
        $this->initClient(new CurlResponseFactory(), $c2_config, $client_config);
    }

    public function sendRequest(RequestInterface $request, array $options = array())
    {
        $curl = new CurlHandler($request, $options);
        $curl->init();
        $curl->exec();
        $curl->release();
        $response = $curl->getResponse();
        if ($response->getStatusCode() < 400) {
            return $response;
        } else {
            throw new RequestException($request, $response);
        }
    }

    public function sendRequestAndMakeResponse(RequestInterface $request, array $options = array())
    {
        if (!$options) {
            $options = $this->clientConfig;
        }

        try {
            $raw_response = $this->sendWithBasicAuth($request, $options);
            $response = $this->responseFactory->create20xResponse($raw_response);
        } catch (RequestException $e) {
            $response = $this->responseFactory->createRequestExceptionResponse($e);
        } catch (InvalidBodyException $e) {
            $response = $this->responseFactory->createInvalidBodyExceptionResponse($e);
        } catch (\Exception $e) {
            $response = $this->responseFactory->createExceptionResponse($e);
        }

        return $response;
    }
}
