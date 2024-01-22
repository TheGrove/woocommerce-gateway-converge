<?php

namespace Elavon\Converge2\Response;

use Elavon\Converge2\DataObject\DataGetter\PagedListDataGetterTrait;
use Elavon\Converge2\DataObject\Resource\Order;

class OrderPagedListResponse extends Response implements PagedListResponseInterface
{
    use PagedListDataGetterTrait;

    protected function castObjectFields()
    {
        $this->castItems(Order::class);
    }
}
