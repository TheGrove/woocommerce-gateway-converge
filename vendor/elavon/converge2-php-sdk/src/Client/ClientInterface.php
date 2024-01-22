<?php

namespace Elavon\Converge2\Client;

use Elavon\Converge2\Client\Response\RawResponseInterface;
use Elavon\Converge2\Request\RequestInterface;
use Elavon\Converge2\Response\ResponseInterface;

/**
 * Converge2 Client Interface.
 *
 * Extends Guzzle Client Interface.
 */
interface ClientInterface
{
    /**
     * @param RequestInterface $request
     * @param array $options
     * @return RawResponseInterface
     */
    public function sendRequest(RequestInterface $request, array $options = array());

    /**
     * Sends request with Basic Auth Headers using Converge2 Secret Key or Public Key as set on the Request.
     *
     * @param RequestInterface $request
     * @param array $options
     * @return RawResponseInterface
     */
    public function sendWithBasicAuth(RequestInterface $request, array $options = array());

    /**
     * Sends request with Basic Auth Headers using Converge2 Public Key.
     *
     * @param RequestInterface $request
     * @param array $options
     * @return RawResponseInterface
     */
    public function sendWithPublicKeyBasicAuth(RequestInterface $request, array $options = array());

    /**
     * Sends request with Basic Auth Headers using Converge2 Secret Key.
     *
     * @param RequestInterface $request
     * @param array $options
     * @return RawResponseInterface
     */
    public function sendWithSecretKeyBasicAuth(RequestInterface $request, array $options = array());

    /**
     * @param RequestInterface $request
     * @return bool
     */
    public function isRequestKeyTypeSecret(RequestInterface $request);

    /**
     * @param RequestInterface $request
     * @param array $options
     * @return ResponseInterface
     */
    public function sendRequestAndMakeResponse(RequestInterface $request, array $options = array());
}
