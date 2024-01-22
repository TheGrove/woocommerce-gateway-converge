<?php

namespace Elavon\Converge2\Request;

use Elavon\Converge2\Message\MessageInterface;

interface RequestInterface extends MessageInterface
{
    /**
     * @return string
     */
    public function getMethod();

    /**
     * @return string
     */
    public function getUri();
}
