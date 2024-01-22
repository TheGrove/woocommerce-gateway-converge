<?php

namespace Elavon\Converge2\Response;

use Elavon\Converge2\DataObject\DataGetter\PlanDataGetterTrait;

class PlanResponse extends Response implements PlanResponseInterface
{
    use PlanDataGetterTrait;
}
