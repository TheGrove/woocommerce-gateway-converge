<?php

namespace Elavon\Converge2\Response;

use Elavon\Converge2\DataObject\DataGetter\SignerDataGetterTrait;

class SignerResponse extends Response implements SignerResponseInterface
{
    use SignerDataGetterTrait;
}
