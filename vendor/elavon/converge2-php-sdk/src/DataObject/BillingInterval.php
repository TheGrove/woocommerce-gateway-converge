<?php

namespace Elavon\Converge2\DataObject;

final class BillingInterval extends AbstractDataObject
{

    protected function castObjectFields()
    {
        $this->castTimeUnit();
    }

    /**
     * @return TimeUnit|null
     */
    public function getTimeUnit()
    {
        return $this->getDataField(C2ApiFieldName::TIME_UNIT);
    }

    /**
     * @return number|null
     */
    public function getCount()
    {
        return $this->getDataField(C2ApiFieldName::COUNT);
    }

    protected function castTimeUnit()
    {
        $this->castToDataObjectClass(C2ApiFieldName::TIME_UNIT, TimeUnit::class);
    }
}