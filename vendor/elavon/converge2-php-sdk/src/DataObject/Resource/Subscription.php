<?php

namespace Elavon\Converge2\DataObject\Resource;

use Elavon\Converge2\DataObject\DataGetter\SubscriptionDataGetterTrait;

class Subscription extends AbstractResource implements SubscriptionInterface
{
    use SubscriptionDataGetterTrait;
}
