<?php

namespace Elavon\Converge2\Response;

use Elavon\Converge2\DataObject\DataGetter\TransactionDataGetterTrait;

class TransactionResponse extends Response implements TransactionResponseInterface
{
    use TransactionDataGetterTrait;
}
