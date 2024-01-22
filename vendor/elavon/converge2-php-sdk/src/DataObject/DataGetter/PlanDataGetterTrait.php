<?php

namespace Elavon\Converge2\DataObject\DataGetter;

use Elavon\Converge2\DataObject\BillingInterval;
use Elavon\Converge2\DataObject\C2ApiFieldName;
use Elavon\Converge2\DataObject\DataGetter\Field\BillCountGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\CreatedAtGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\CustomFieldsGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\CustomReferenceGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\DeletedAtGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\DescriptionGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\HrefGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\IdGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\InitialTotalGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\MerchantGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\ModifiedAtGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\ShopperStatementGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\TotalGetterTrait;

/**
 * @method getDataField($field)
 * @method castToDataObjectClass($field, $class)
 */
trait PlanDataGetterTrait
{
    use IdGetterTrait;
    use HrefGetterTrait;
    use CreatedAtGetterTrait;
    use ModifiedAtGetterTrait;
    use DeletedAtGetterTrait;
    use MerchantGetterTrait;
    use DescriptionGetterTrait;
    use TotalGetterTrait;
    use BillCountGetterTrait;
    use InitialTotalGetterTrait;
    use ShopperStatementGetterTrait;
    use CustomReferenceGetterTrait;
    use CustomFieldsGetterTrait;

    protected function castObjectFields()
    {
        $this->castBillingInterval();
        $this->castTotal();
        $this->castInitialTotal();
        $this->castShopperStatement();
    }

    /**
     * @return string|null
     */
    public function getName()
    {
        return $this->getDataField(C2ApiFieldName::NAME);
    }

    /**
     * @return BillingInterval|null
     */
    public function getBillingInterval()
    {
        return $this->getDataField(C2ApiFieldName::BILLING_INTERVAL);
    }

    /**
     * @return number|null
     */
    public function getInitialTotalBillCount()
    {
        return $this->getDataField(C2ApiFieldName::INITIAL_TOTAL_BILL_COUNT);
    }

    /**
     * @return bool|null
     */
    public function getIsSubscribable()
    {
        return $this->getDataField(C2ApiFieldName::IS_SUBSCRIBABLE);
    }

    protected function castBillingInterval()
    {
        $this->castToDataObjectClass(C2ApiFieldName::BILLING_INTERVAL, BillingInterval::class);
    }
}
