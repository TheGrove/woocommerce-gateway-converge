<?php

namespace Elavon\Converge2\Request;

class GenericUpdateRequest extends AbstractUpdateRequest
{
    public function __construct($endpoint, $id, $data)
    {
        $this->endpoint = $endpoint;
        parent::__construct($id, $data);
    }
}