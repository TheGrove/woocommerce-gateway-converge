<?php

namespace Elavon\Converge2\DataObject\Resource;

use Elavon\Converge2\DataObject\DataGetter\BatchDataGetterTrait;

class Batch extends AbstractResource implements BatchInterface
{
    use BatchDataGetterTrait;
}