<?php

namespace Elavon\Converge2\Response;

use Elavon\Converge2\DataObject\DataGetter\HostedCardDataGetterTrait;

class HostedCardResponse extends Response implements HostedCardResponseInterface
{
    use HostedCardDataGetterTrait;
}
