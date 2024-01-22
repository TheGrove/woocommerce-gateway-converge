<?php

namespace Elavon\Converge2\Response;

use Elavon\Converge2\DataObject\DataGetter\PaymentLinkDataGetterTrait;

class PaymentLinkResponse extends Response implements PaymentLinkResponseInterface
{
    use PaymentLinkDataGetterTrait;
}
