<?php

namespace Elavon\Converge2\Client\Response;

use Elavon\Converge2\Message\MessageInterface;

interface RawResponseInterface extends MessageInterface
{
    /**
     * @return int
     */
    public function getStatusCode();

    /**
     * @return string
     */
    public function getReasonPhrase();
}
