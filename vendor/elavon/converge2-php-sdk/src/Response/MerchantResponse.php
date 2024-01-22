<?php

namespace Elavon\Converge2\Response;

use Elavon\Converge2\DataObject\DataGetter\MerchantDataGetterTrait;

class MerchantResponse extends Response implements MerchantResponseInterface
{
    use MerchantDataGetterTrait;
}
