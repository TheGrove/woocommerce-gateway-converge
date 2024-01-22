<?php

namespace Elavon\Converge2\DataObject\Resource;

use Elavon\Converge2\DataObject\DataGetter\StoredCardDataGetterTrait;

class StoredCard extends AbstractResource implements StoredCardInterface
{
    use StoredCardDataGetterTrait;
}