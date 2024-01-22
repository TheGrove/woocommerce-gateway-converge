<?php

namespace Elavon\Converge2\DataObject\DataGetter\Field;

use Elavon\Converge2\DataObject\C2ApiFieldName;

/**
 * @method getDataField($field)
 */
trait HostedCardGetterTrait
{
    /**
     * @return string|null
     */
    public function getHostedCard()
    {
        return $this->getDataField(C2ApiFieldName::HOSTED_CARD);
    }
}
