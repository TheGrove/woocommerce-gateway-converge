<?php

namespace Elavon\Converge2\Response;

use Elavon\Converge2\DataObject\DataGetter\OrderDataGetterTrait;

class OrderResponse extends Response implements OrderResponseInterface
{
    use OrderDataGetterTrait;
}
