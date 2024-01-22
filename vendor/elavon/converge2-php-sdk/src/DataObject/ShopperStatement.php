<?php

namespace Elavon\Converge2\DataObject;

final class ShopperStatement extends AbstractDataObject
{
    /**
     * @return string|null
     */
    public function getName()
    {
        return $this->getDataField(C2ApiFieldName::NAME);
    }

    /**
     * @return string|null
     */
    public function getPhone()
    {
        return $this->getDataField(C2ApiFieldName::PHONE);
    }

    /**
     * @return string|null
     */
    public function getUrl()
    {
        return $this->getDataField(C2ApiFieldName::URL);
    }
}
