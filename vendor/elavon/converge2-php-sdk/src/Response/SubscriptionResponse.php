<?php

namespace Elavon\Converge2\Response;

use Elavon\Converge2\DataObject\DataGetter\SubscriptionDataGetterTrait;

class SubscriptionResponse extends Response implements SubscriptionResponseInterface
{
    use SubscriptionDataGetterTrait;
}
