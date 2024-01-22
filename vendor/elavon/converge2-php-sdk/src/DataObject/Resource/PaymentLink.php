<?php

namespace Elavon\Converge2\DataObject\Resource;

use Elavon\Converge2\DataObject\DataGetter\PaymentLinkDataGetterTrait;

class PaymentLink extends AbstractResource implements PaymentLinkInterface
{
    use PaymentLinkDataGetterTrait;
}
