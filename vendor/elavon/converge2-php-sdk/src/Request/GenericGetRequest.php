<?php

namespace Elavon\Converge2\Request;

class GenericGetRequest extends AbstractGetRequest
{
    public function __construct($endpoint, $id)
    {
        $this->endpoint = $endpoint;
        parent::__construct($id);
    }
}