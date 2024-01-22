<?php

namespace Elavon\Converge2\DataObject;

final class ValueToken extends AbstractDataObject
{
    /**
     * @return string|null
     */
    public function getType()
    {
        return $this->getDataField(C2ApiFieldName::TYPE);
    }

    /**
     * @return string|null
     */
    public function getToken()
    {
        return $this->getDataField(C2ApiFieldName::TOKEN);
    }

    /**
     * @return string|null
     */
    public function getProvider()
    {
        return $this->getDataField(C2ApiFieldName::PROVIDER);
    }
}
