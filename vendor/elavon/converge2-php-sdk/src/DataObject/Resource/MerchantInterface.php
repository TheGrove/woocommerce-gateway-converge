<?php

namespace Elavon\Converge2\DataObject\Resource;

interface MerchantInterface
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
    public function getLegalName();
}