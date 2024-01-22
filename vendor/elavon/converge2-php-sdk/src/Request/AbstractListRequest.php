<?php

namespace Elavon\Converge2\Request;

abstract class AbstractListRequest extends AbstractRequest
{
    protected $method = 'GET';
    protected $keyType = self::KEY_TYPE_SECRET;
    protected $queryStr;

    public function __construct($query_str = '')
    {
        $this->queryStr = $query_str;
        if ($this->queryStr) {
            $this->endpoint .= '?' . $this->queryStr;
        }
        parent::__construct($this->method, $this->endpoint);
    }
}