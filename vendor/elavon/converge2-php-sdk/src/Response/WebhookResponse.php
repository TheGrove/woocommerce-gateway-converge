<?php

namespace Elavon\Converge2\Response;

use Elavon\Converge2\DataObject\DataGetter\WebhookDataGetterTrait;

class WebhookResponse extends Response implements WebhookResponseInterface
{
    use WebhookDataGetterTrait;
}
