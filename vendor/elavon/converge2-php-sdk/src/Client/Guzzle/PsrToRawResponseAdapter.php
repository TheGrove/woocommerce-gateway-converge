<?php

namespace Elavon\Converge2\Client\Guzzle;

use Elavon\Converge2\Client\Response\RawResponseInterface;
use Psr\Http\Message\ResponseInterface;

class PsrToRawResponseAdapter implements RawResponseInterface
{
    /** @var ResponseInterface */
    protected $psrResponse;

    public function __construct(ResponseInterface $psr_response)
    {
        $this->psrResponse = $psr_response;
    }

    public function getBody()
    {
        return (string)$this->psrResponse->getBody();
    }

    public function getHeaders()
    {
        return $this->psrResponse->getHeaders();
    }

    public function getHeader($header)
    {
        return $this->psrResponse->getHeader($header);
    }

    public function getHeaderLine($header)
    {
        return $this->psrResponse->getHeaderLine($header);
    }

    public function hasHeader($header)
    {
        return $this->psrResponse->hasHeader($header);
    }

    public function getProtocolVersion()
    {
        return $this->psrResponse->getProtocolVersion();
    }

    public function getReasonPhrase()
    {
        return $this->psrResponse->getReasonPhrase();
    }

    public function getStatusCode()
    {
        return $this->psrResponse->getStatusCode();
    }
}