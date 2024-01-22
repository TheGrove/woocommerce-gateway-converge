<?php

namespace Elavon\Converge2\Response;

use Elavon\Converge2\DataObject\DataGetter\ForexAdviceDataGetterTrait;

class ForexAdviceResponse extends Response implements ForexAdviceResponseInterface
{
    use ForexAdviceDataGetterTrait;
}
