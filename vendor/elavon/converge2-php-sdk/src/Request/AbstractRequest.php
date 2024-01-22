<?php

namespace Elavon\Converge2\Request;

use Elavon\Converge2\Message\MessageTrait;

abstract class AbstractRequest implements RequestInterface
{
    use MessageTrait;

    const KEY_TYPE_PUBLIC = 'public';
    const KEY_TYPE_SECRET = 'secret';

    protected $endpoint;
    protected $method;
    protected $keyType;

    public function __construct($method, $endpoint, array $headers = array(), $body = '')
    {
        $this->method = $method;
        $this->endpoint = $endpoint;
        $this->setHeaders($headers);
        $this->body = $body;
    }

    public function isKeyTypeSecret()
    {
        return $this->keyType == self::KEY_TYPE_SECRET;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getUri()
    {
        return $this->endpoint;
    }
}
