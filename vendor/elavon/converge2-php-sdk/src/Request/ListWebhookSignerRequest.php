<?php

namespace Elavon\Converge2\Request;

use Elavon\Converge2\DataObject\Resource\Endpoint;

class ListWebhookSignerRequest extends AbstractListRequest
{
    protected $endpoint = Endpoint::WEBHOOK;
    protected $id;

    public function __construct($shopper_id, $query_str = '')
    {
        $this->id = $shopper_id;
        $this->endpoint .= '/' . $this->id . '/' . Endpoint::SIGNER;
        parent::__construct($query_str);
    }
}