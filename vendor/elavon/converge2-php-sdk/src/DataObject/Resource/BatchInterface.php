<?php

namespace Elavon\Converge2\DataObject\Resource;

use Elavon\Converge2\DataObject\BatchState;
use Elavon\Converge2\DataObject\CountAndTotal;

interface BatchInterface
{
    /**
     * @return string|null
     */
    public function getId();

    /**
     * @return string|null
     */
    public function getHref();

    /**
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * @return string|null
     */
    public function getModifiedAt();

    /**
     * @return string|null
     */
    public function getMerchant();

    /**
     * @return string|null
     */
    public function getProcessorAccount();

    /**
     * @return string|null
     */
    public function getProcessorReference();

    /**
     * @return BatchState|null
     */
    public function getState();

    /**
     * @return CountAndTotal|null
     */
    public function getCredits();

    /**
     * @return CountAndTotal|null
     */
    public function getDebits();

    /**
     * @return CountAndTotal|null
     */
    public function getNet();
}