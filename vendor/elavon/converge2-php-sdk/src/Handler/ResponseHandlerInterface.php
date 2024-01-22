<?php

namespace Elavon\Converge2\Handler;

use Elavon\Converge2\Response\ResponseInterface;

interface ResponseHandlerInterface
{
    /**
     * @param ResponseInterface $response
     * @return null
     */
    public function handle(ResponseInterface $response);
}
