<?php

namespace Elavon\Converge2\DataObject\Resource;

use Elavon\Converge2\DataObject\DataGetter\OrderDataGetterTrait;

class Order extends AbstractResource implements OrderInterface
{
    use OrderDataGetterTrait;
}