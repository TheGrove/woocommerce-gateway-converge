<?php

namespace Elavon\Converge2\Request\Payload;

use Elavon\Converge2\Request\Payload\DataSetter\ShopperSetterTrait;
use Elavon\Converge2\Request\Payload\DataSetter\HostedCardSetterTrait;
use Elavon\Converge2\Request\Payload\DataSetter\CardSetterTrait;
use Elavon\Converge2\Request\Payload\DataSetter\CustomReferenceSetterTrait;
use Elavon\Converge2\Request\Payload\DataSetter\CustomFieldsSetterTrait;

class StoredCardDataBuilder extends AbstractDataBuilder
{
    use ShopperSetterTrait;
    use HostedCardSetterTrait;
    use CardSetterTrait;
    use CustomReferenceSetterTrait;
    use CustomFieldsSetterTrait;
}
