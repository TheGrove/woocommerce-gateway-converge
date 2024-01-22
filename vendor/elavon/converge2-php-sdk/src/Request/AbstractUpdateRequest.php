<?php

namespace Elavon\Converge2\Request;

abstract class AbstractUpdateRequest extends AbstractCreateRequest
{
    protected $id;

    public function __construct($id, $data)
    {
        $this->id = $id;
        $this->endpoint .= '/' . $this->id;
        parent::__construct($data);
    }
}