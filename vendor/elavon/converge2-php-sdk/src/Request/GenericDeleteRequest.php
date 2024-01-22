<?php

namespace Elavon\Converge2\Request;

class GenericDeleteRequest extends AbstractDeleteRequest
{
    public function __construct($endpoint, $id)
    {
        $this->endpoint = $endpoint;
        parent::__construct($id);
    }
}