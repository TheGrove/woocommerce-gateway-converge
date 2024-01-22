<?php

namespace Elavon\Converge2\DataObject\DataGetter;

use Elavon\Converge2\DataObject\C2ApiFieldName;
use Elavon\Converge2\DataObject\DataGetter\Field\CreatedAtGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\HrefGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\IdGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\MerchantGetterTrait;
use Elavon\Converge2\DataObject\EventType;
use Elavon\Converge2\DataObject\ResourceType;

/**
 * @method getDataField($field)
 * @method castToDataObjectClass($field, $class)
 */
trait NotificationDataGetterTrait
{
    use IdGetterTrait;
    use HrefGetterTrait;
    use CreatedAtGetterTrait;
    use MerchantGetterTrait;

    protected function castObjectFields()
    {
        $this->castEventType();
        $this->castResourceType();
    }

    /**
     * @return EventType|null
     */
    public function getEventType()
    {
        return $this->getDataField(C2ApiFieldName::EVENT_TYPE);
    }

    /**
     * @return ResourceType|null
     */
    public function getResourceType()
    {
        return $this->getDataField(C2ApiFieldName::RESOURCE_TYPE);
    }

    /**
     * @return string|null
     */
    public function getResource()
    {
        return $this->getDataField(C2ApiFieldName::RESOURCE);
    }

    protected function castEventType()
    {
        $this->castToDataObjectClass(C2ApiFieldName::EVENT_TYPE, EventType::class);
    }

    protected function castResourceType()
    {
        $this->castToDataObjectClass(C2ApiFieldName::RESOURCE_TYPE, ResourceType::class);
    }
}
