<?php

namespace Elavon\Converge2\Client\Guzzle;

use Elavon\Converge2\Request\RequestInterface;

class ConvergeToPsrRequestAdapter extends \GuzzleHttp\Psr7\Request {

    public function __construct(RequestInterface $request)
    {
        parent::__construct(
            $request->getMethod(),
            $request->getUri(),
            $request->getHeaders(),
            $request->getBody(),
            $request->getProtocolVersion()
        );
    }
}
