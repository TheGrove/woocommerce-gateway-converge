<?php

namespace Elavon\Converge2\Response;

use Elavon\Converge2\DataObject\Resource\StoredCardInterface;

interface StoredCardResponseInterface extends ResponseInterface, StoredCardInterface
{
    /**
     * @return bool
     */
    public function hasFailuresAboutCardAlreadyExists();
}
