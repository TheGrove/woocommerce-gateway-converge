<?php

namespace Elavon\Converge2\DataObject\Resource;

use Elavon\Converge2\DataObject\DataGetter\PaymentSessionDataGetterTrait;

class PaymentSession extends AbstractResource implements PaymentSessionInterface
{
    use PaymentSessionDataGetterTrait;
}
