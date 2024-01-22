<?php

namespace Elavon\Converge2\Response;

use Elavon\Converge2\DataObject\DataGetter\ShopperDataGetterTrait;

class ShopperResponse extends Response implements ShopperResponseInterface
{
    use ShopperDataGetterTrait;
}
