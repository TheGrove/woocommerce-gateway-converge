<?php

namespace Elavon\Converge2\Request\Payload;

use Elavon\Converge2\DataObject\C2ApiFieldName;
use Elavon\Converge2\Request\Payload\DataSetter\CustomFieldsSetterTrait;
use Elavon\Converge2\Request\Payload\DataSetter\CustomReferenceSetterTrait;
use Elavon\Converge2\Request\Payload\DataSetter\ShopperInteractionSetterTrait;
use Elavon\Converge2\Request\Payload\DataSetter\StoredCardSetterTrait;
use Elavon\Converge2\Request\Payload\DataSetter\TotalSetterTrait;

class ForexAdviceDataBuilder extends AbstractDataBuilder
{
    use StoredCardSetterTrait;
    use TotalSetterTrait;
    use ShopperInteractionSetterTrait;
    use CustomReferenceSetterTrait;
    use CustomFieldsSetterTrait;

    public function setProcessorAccount($value)
    {
        $this->setField(C2ApiFieldName::PROCESSOR_ACCOUNT, $value);
    }

    public function setCardNumber($value)
    {
        $this->setField(C2ApiFieldName::CARD_NUMBER, $value);
    }
}
