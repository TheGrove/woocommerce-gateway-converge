<?php

namespace Elavon\Converge2\DataObject\Resource;

use Elavon\Converge2\DataObject\DataGetter\WebhookDataGetterTrait;

class Webhook extends AbstractResource implements WebhookInterface
{
    use WebhookDataGetterTrait;
}
