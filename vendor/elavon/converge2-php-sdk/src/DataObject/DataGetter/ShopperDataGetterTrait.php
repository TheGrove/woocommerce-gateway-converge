<?php

namespace Elavon\Converge2\DataObject\DataGetter;

use Elavon\Converge2\DataObject\Address;
use Elavon\Converge2\DataObject\C2ApiFieldName;
use Elavon\Converge2\DataObject\DataGetter\Field\CreatedAtGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\CustomFieldsGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\CustomReferenceGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\DeletedAtGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\DescriptionGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\HrefGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\IdGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\MerchantGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\ModifiedAtGetterTrait;

/**
 * @method getDataField($field)
 * @method castToDataObjectClass($field, $class)
 */
trait ShopperDataGetterTrait
{
    use IdGetterTrait;
    use HrefGetterTrait;
    use CreatedAtGetterTrait;
    use ModifiedAtGetterTrait;
    use DeletedAtGetterTrait;
    use MerchantGetterTrait;
    use DescriptionGetterTrait;
    use CustomReferenceGetterTrait;
    use CustomFieldsGetterTrait;

    protected function castObjectFields()
    {
        $this->castPrimaryAddress();
    }

    /**
     * @return string|null
     */
    public function getDefaultStoredCard()
    {
        return $this->getDataField(C2ApiFieldName::DEFAULT_STORED_CARD);
    }

    /**
     * @return string|null
     */
    public function getFullName()
    {
        return $this->getDataField(C2ApiFieldName::FULL_NAME);
    }

    /**
     * @return string|null
     */
    public function getCompany()
    {
        return $this->getDataField(C2ApiFieldName::COMPANY);
    }

    /**
     * @return Address|null
     */
    public function getPrimaryAddress()
    {
        return $this->getDataField(C2ApiFieldName::PRIMARY_ADDRESS);
    }

    /**
     * @return string|null
     */
    public function getPrimaryPhone()
    {
        return $this->getDataField(C2ApiFieldName::PRIMARY_PHONE);
    }

    /**
     * @return string|null
     */
    public function getAlternatePhone()
    {
        return $this->getDataField(C2ApiFieldName::ALTERNATE_PHONE);
    }

    /**
     * @return string|null
     */
    public function getFax()
    {
        return $this->getDataField(C2ApiFieldName::FAX);
    }

    /**
     * @return string|null
     */
    public function getEmail()
    {
        return $this->getDataField(C2ApiFieldName::EMAIL);
    }

    protected function castPrimaryAddress()
    {
        $this->castToDataObjectClass(C2ApiFieldName::PRIMARY_ADDRESS, Address::class);
    }
}
