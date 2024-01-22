<?php

namespace Elavon\Converge2\Client\Curl\Exception;

use Elavon\Converge2\Client\Response\RawResponseInterface;
use Elavon\Converge2\Request\RequestInterface;
use Throwable;

class RequestException extends \Exception
{
    /** @var RequestInterface */
    protected $request;

    /** @var RawResponseInterface */
    protected $response;

    public function __construct(RequestInterface $request, RawResponseInterface $response, $message = "", $code = 0, Throwable $previous = null)
    {
        $this->request = $request;
        $this->response = $response;

        parent::__construct($message, $code, $previous);
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getResponse()
    {
        return $this->response;
    }
}