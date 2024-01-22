<?php

namespace Elavon\Converge2\DataObject\Resource;

use Elavon\Converge2\DataObject\DataGetter\SignerDataGetterTrait;

class Signer extends AbstractResource implements SignerInterface
{
    use SignerDataGetterTrait;
}
