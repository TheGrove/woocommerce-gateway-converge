<?php

namespace Elavon\Converge2\DataObject\Resource;

use Elavon\Converge2\DataObject\DataGetter\MerchantDataGetterTrait;

class Merchant extends AbstractResource implements MerchantInterface
{
    use MerchantDataGetterTrait;
}