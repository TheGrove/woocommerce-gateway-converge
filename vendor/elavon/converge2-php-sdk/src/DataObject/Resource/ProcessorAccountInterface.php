<?php

namespace Elavon\Converge2\DataObject\Resource;

use Elavon\Converge2\DataObject\Address;

interface ProcessorAccountInterface
{
    /**
     * @return string|null
     */
    public function getHref();

    /**
     * @return string|null
     */
    public function getId();

    /**
     * @return string|null
     */
    public function getMerchant();

    /**
     * @return string|null
     */
    public function getProcessorReference();

    /**
     * @return string|null
     */
    public function getTradeName();

    /**
     * @return Address|null
     */
    public function getBusinessAddress();

    /**
     * @return string|null
     */
    public function getBusinessPhone();

    /**
     * @return string|null
     */
    public function getBusinessEmail();

    /**
     * @return string|null
     */
    public function getBusinessWebsite();

    /**
     * @return string|null
     */
    public function getMerchantCategoryCode();

    /**
     * @return string|null
     */
    public function getSettlementCurrencyCode();

    /**
     * @return array|null
     */
    public function getSupportedCardBrands();
}