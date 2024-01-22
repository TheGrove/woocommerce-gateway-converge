<?php

namespace Elavon\Converge2\Client\Response;

use Elavon\Converge2\Message\MessageTrait;

abstract class AbstractRawResponse implements RawResponseInterface
{
    use MessageTrait;

    /** @var string */
    protected $reasonPhrase = '';

    /** @var int */
    protected $statusCode = 200;

    public function __construct(
        $status = 200,
        array $headers = array(),
        $body = null,
        $version = '1.1',
        $reason = null
    ) {
        $status = (int)$status;
        $this->statusCode = $status;

        if ($body !== '' && $body !== null) {
            $this->body = $body;
        }

        $this->setHeaders($headers);
        $this->reasonPhrase = (string)$reason;
        $this->protocol = $version;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getReasonPhrase()
    {
        return $this->reasonPhrase;
    }
}
