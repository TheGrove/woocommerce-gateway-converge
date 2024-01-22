<?php

namespace Elavon\Converge2\Request;

class GenericCreateRequest extends AbstractCreateRequest
{
    public function __construct($endpoint, $data)
    {
        $this->endpoint = $endpoint;
        parent::__construct($data);
    }
}