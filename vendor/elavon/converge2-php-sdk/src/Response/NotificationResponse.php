<?php

namespace Elavon\Converge2\Response;

use Elavon\Converge2\DataObject\DataGetter\NotificationDataGetterTrait;

class NotificationResponse extends Response implements NotificationResponseInterface
{
    use NotificationDataGetterTrait;
}
