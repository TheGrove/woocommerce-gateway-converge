<?php

namespace Elavon\Converge2\DataObject\DataGetter;

use Elavon\Converge2\DataObject\C2ApiFieldName;
use Elavon\Converge2\DataObject\DataGetter\Field\HrefGetterTrait;

/**
 * @method getDataField($field)
 */
trait PagedListDataGetterTrait
{
    use HrefGetterTrait;

    /**
     * @return string|null
     */
    public function getFirst()
    {
        return $this->getDataField(C2ApiFieldName::FIRST);
    }

    /**
     * @return string|null
     */
    public function getNext()
    {
        return $this->getDataField(C2ApiFieldName::NEXT);
    }

    /**
     * @return string|null
     */
    public function getPageToken()
    {
        return $this->getDataField(C2ApiFieldName::PAGE_TOKEN);
    }

    /**
     * @return string|null
     */
    public function getNextPageToken()
    {
        return $this->getDataField(C2ApiFieldName::NEXT_PAGE_TOKEN);
    }

    /**
     * @return number|null
     */
    public function getSize()
    {
        return $this->getDataField(C2ApiFieldName::SIZE);
    }

    /**
     * @return number|null
     */
    public function getLimit()
    {
        return $this->getDataField(C2ApiFieldName::LIMIT);
    }

    /**
     * @return array|null
     */
    public function getItems()
    {
        return $this->getDataField(C2ApiFieldName::ITEMS);
    }

    protected function castItems($class)
    {
        $this->castToDataObjectClass(C2ApiFieldName::ITEMS, $class);
    }
}
