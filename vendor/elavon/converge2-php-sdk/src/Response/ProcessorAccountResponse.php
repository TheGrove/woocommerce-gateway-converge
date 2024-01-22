<?php

namespace Elavon\Converge2\Response;

use Elavon\Converge2\DataObject\DataGetter\ProcessorAccountDataGetterTrait;

class ProcessorAccountResponse extends Response implements ProcessorAccountResponseInterface
{
    use ProcessorAccountDataGetterTrait;
}
