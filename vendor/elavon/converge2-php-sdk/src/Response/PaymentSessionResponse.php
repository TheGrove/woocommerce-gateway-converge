<?php

namespace Elavon\Converge2\Response;

use Elavon\Converge2\DataObject\DataGetter\PaymentSessionDataGetterTrait;

class PaymentSessionResponse extends Response implements PaymentSessionResponseInterface
{
    use PaymentSessionDataGetterTrait;
}
