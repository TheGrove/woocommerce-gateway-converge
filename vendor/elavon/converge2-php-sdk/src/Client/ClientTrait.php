<?php

namespace Elavon\Converge2\Client;

use Elavon\Converge2\Request\AbstractRequest;
use Elavon\Converge2\Request\RequestInterface;
use Elavon\Converge2\Client\Response\ResponseFactoryInterface;

trait ClientTrait {
    /** @var ClientConfigInterface */
    protected $c2Config;

    /** @var array */
    protected $clientConfig = array();

    /** @var ResponseFactoryInterface */
    protected $responseFactory;

    protected function initClient(
        ResponseFactoryInterface $response_factory,
        ClientConfigInterface $c2_config,
        array $client_config = array()
    ) {
        $this->responseFactory = $response_factory;
        $this->c2Config = $c2_config;
        $this->clientConfig = $client_config;

        $this->clientConfig['base_uri'] = $this->c2Config->getBaseUri();
        $this->clientConfig['headers']['Accept-Version'] = $this->c2Config->getApiVersion();

        $proxy = $this->c2Config->getProxy();
        if ($proxy) {
            $this->clientConfig['proxy'] = $proxy;
        }

        $timeout = $this->c2Config->getTimeout();
        if ($timeout) {
            $this->clientConfig['timeout'] = $timeout;
        }
    }

    public function sendWithPublicKeyBasicAuth(RequestInterface $request, array $options = array())
    {
        $options['auth'] = $this->c2Config->getPublicKeyBasicAuthConfig();
        return $this->sendRequest($request, $options);
    }

    public function sendWithSecretKeyBasicAuth(RequestInterface $request, array $options = array())
    {
        $options['auth'] = $this->c2Config->getSecretKeyBasicAuthConfig();
        return $this->sendRequest($request, $options);
    }

    public function sendWithBasicAuth(RequestInterface $request, array $options = array())
    {
        return $this->isRequestKeyTypeSecret($request)
            ? $this->sendWithSecretKeyBasicAuth($request, $options)
            : $this->sendWithPublicKeyBasicAuth($request, $options);
    }

    public function isRequestKeyTypeSecret(RequestInterface $request) {
        if ($request instanceof AbstractRequest) {
            return $request->isKeyTypeSecret();
        }
        else {
            return true;
        }
    }
}