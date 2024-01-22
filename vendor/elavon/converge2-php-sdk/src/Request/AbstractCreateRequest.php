<?php

namespace Elavon\Converge2\Request;

abstract class AbstractCreateRequest extends AbstractRequest
{
    protected $method = 'POST';
    protected $keyType = self::KEY_TYPE_SECRET;

    public function __construct($data)
    {
        $body = json_encode($data);
        $headers = $this->addJsonContentTypeHeader(array());
        parent::__construct($this->method, $this->endpoint, $headers, $body);
    }

    protected function addJsonContentTypeHeader(array $headers)
    {
        $headers['Content-Type'] = 'application/json';
        return $headers;
    }
}