<?php

namespace Elavon\Converge2\Request;

class GenericListRequest extends AbstractListRequest
{
    public function __construct($endpoint, $query_str = '')
    {
        $this->endpoint = $endpoint;
        parent::__construct($query_str);
    }
}