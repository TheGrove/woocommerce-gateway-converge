<?php

namespace Elavon\Converge2\Response;

use Elavon\Converge2\DataObject\DataGetter\BatchDataGetterTrait;

class BatchResponse extends Response implements BatchResponseInterface
{
    use BatchDataGetterTrait;
}
