<?php

namespace Elavon\Converge2\DataObject\DataGetter;

use Elavon\Converge2\DataObject\C2ApiFieldName;
use Elavon\Converge2\DataObject\DataGetter\Field\BillCountGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\CreatedAtGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\CustomFieldsGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\CustomReferenceGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\DebtorAccountGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\HrefGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\IdGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\MerchantGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\ModifiedAtGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\ShopperGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\StoredCardGetterTrait;
use Elavon\Converge2\DataObject\SubscriptionState;

/**
 * @method getDataField($field)
 * @method castToDataObjectClass($field, $class)
 */
trait SubscriptionDataGetterTrait
{
    use IdGetterTrait;
    use HrefGetterTrait;
    use CreatedAtGetterTrait;
    use ModifiedAtGetterTrait;
    use MerchantGetterTrait;
    use ShopperGetterTrait;
    use DebtorAccountGetterTrait;
    use StoredCardGetterTrait;
    use BillCountGetterTrait;
    use CustomReferenceGetterTrait;
    use CustomFieldsGetterTrait;

    protected function castObjectFields()
    {
        $this->castDebtorAccount();
        $this->castSubscriptionState();
    }

    /**
     * @return string|null
     */
    public function getPlan()
    {
        return $this->getDataField(C2ApiFieldName::PLAN);
    }

    /**
     * @return bool|null
     */
    public function getDoForexConversion()
    {
        return $this->getDataField(C2ApiFieldName::DO_FOREX_CONVERSION);
    }

    /**
     * @return string|null
     */
    public function getTimeZoneId()
    {
        return $this->getDataField(C2ApiFieldName::TIME_ZONE_ID);
    }

    /**
     * @return string|null
     */
    public function getFirstBillAt()
    {
        return $this->getDataField(C2ApiFieldName::FIRST_BILL_AT);
    }

    /**
     * @return string|null
     */
    public function getNextBillAt()
    {
        return $this->getDataField(C2ApiFieldName::NEXT_BILL_AT);
    }

    /**
     * @return string|null
     */
    public function getPreviousBillAt()
    {
        return $this->getDataField(C2ApiFieldName::PREVIOUS_BILL_AT);
    }

    /**
     * @return string|null
     */
    public function getFinalBillAt()
    {
        return $this->getDataField(C2ApiFieldName::FINAL_BILL_AT);
    }

    /**
     * @return string|null
     */
    public function getCancelRequestedAt()
    {
        return $this->getDataField(C2ApiFieldName::CANCEL_REQUESTED_AT);
    }

    /**
     * @return number|null
     */
    public function getCancelAfterBillNumber()
    {
        return $this->getDataField(C2ApiFieldName::CANCEL_AFTER_BILL_NUMBER);
    }

    /**
     * @return number|null
     */
    public function getNextBillNumber()
    {
        return $this->getDataField(C2ApiFieldName::NEXT_BILL_NUMBER);
    }

    /**
     * @return SubscriptionState|null
     */
    public function getSubscriptionState()
    {
        return $this->getDataField(C2ApiFieldName::SUBSCRIPTION_STATE);
    }

    /**
     * @return number|null
     */
    public function getFailureCount()
    {
        return $this->getDataField(C2ApiFieldName::FAILURE_COUNT);
    }

    protected function castSubscriptionState()
    {
        $this->castToDataObjectClass(C2ApiFieldName::SUBSCRIPTION_STATE, SubscriptionState::class);
    }
}
