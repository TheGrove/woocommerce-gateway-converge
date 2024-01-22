<?php

namespace Elavon\Converge2\DataObject\DataGetter;

use Elavon\Converge2\DataObject\BatchState;
use Elavon\Converge2\DataObject\C2ApiFieldName;
use Elavon\Converge2\DataObject\CountAndTotal;
use Elavon\Converge2\DataObject\DataGetter\Field\CreatedAtGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\HrefGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\IdGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\MerchantGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\ModifiedAtGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\ProcessorAccountGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\ProcessorReferenceGetterTrait;

/**
 * @method getDataField($field)
 * @method castToDataObjectClass($field, $class)
 */
trait BatchDataGetterTrait
{
    use IdGetterTrait;
    use HrefGetterTrait;
    use CreatedAtGetterTrait;
    use ModifiedAtGetterTrait;
    use MerchantGetterTrait;
    use ProcessorAccountGetterTrait;
    use ProcessorReferenceGetterTrait;

    protected function castObjectFields()
    {
        $this->castState();
        $this->castCredits();
        $this->castDebits();
        $this->castNet();
    }

    /**
     * @return BatchState|null
     */
    public function getState()
    {
        return $this->getDataField(C2ApiFieldName::STATE);
    }

    protected function castState()
    {
        $this->castToDataObjectClass(C2ApiFieldName::STATE, BatchState::class);
    }

    /**
     * @return CountAndTotal|null
     */
    public function getCredits()
    {
        return $this->getDataField(C2ApiFieldName::CREDITS);
    }

    protected function castCredits()
    {
        $this->castToDataObjectClass(C2ApiFieldName::CREDITS, CountAndTotal::class);
    }

    /**
     * @return CountAndTotal|null
     */
    public function getDebits()
    {
        return $this->getDataField(C2ApiFieldName::DEBITS);
    }

    protected function castDebits()
    {
        $this->castToDataObjectClass(C2ApiFieldName::DEBITS, CountAndTotal::class);
    }

    /**
     * @return CountAndTotal|null
     */
    public function getNet()
    {
        return $this->getDataField(C2ApiFieldName::NET);
    }

    protected function castNet()
    {
        $this->castToDataObjectClass(C2ApiFieldName::NET, CountAndTotal::class);
    }
}