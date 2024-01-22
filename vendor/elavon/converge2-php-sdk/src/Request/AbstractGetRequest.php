<?php

namespace Elavon\Converge2\Request;

use Elavon\Converge2\Converge2;

abstract class AbstractGetRequest extends AbstractRequest
{
    protected $method = 'GET';
    protected $keyType = self::KEY_TYPE_SECRET;
    protected $id;

    public function __construct($id)
    {
        $this->id = $id ? $id : Converge2::NON_EXISTENT_CONVERGE_ID;
        $this->endpoint .= '/' . $this->id;
        parent::__construct($this->method, $this->endpoint);
    }
}
