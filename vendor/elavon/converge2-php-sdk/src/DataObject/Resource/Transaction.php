<?php

namespace Elavon\Converge2\DataObject\Resource;

use Elavon\Converge2\DataObject\DataGetter\TransactionDataGetterTrait;

class Transaction extends AbstractResource implements TransactionInterface
{
    use TransactionDataGetterTrait;
}
