<?php

namespace Elavon\Converge2\Request;

use Elavon\Converge2\DataObject\Resource\Endpoint;

class ListShopperStoredCardRequest extends AbstractListRequest
{
    protected $endpoint = Endpoint::SHOPPER;
    protected $id;

    public function __construct($shopper_id, $query_str = '')
    {
        $this->id = $shopper_id;
        $this->endpoint .= '/' . $this->id . '/' . Endpoint::STORED_CARD;
        parent::__construct($query_str);
    }
}