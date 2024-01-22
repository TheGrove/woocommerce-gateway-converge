<?php

namespace Elavon\Converge2\Message;

interface MessageInterface
{
    /**
     * @return string
     */
    public function getProtocolVersion();

    /**
     * @return array
     */
    public function getHeaders();

    /**
     * @param string $header
     * @return bool
     */
    public function hasHeader($header);

    /**
     * @param string $header
     * @return string
     */
    public function getHeader($header);

    /**
     * @param string $header
     * @return string
     */
    public function getHeaderLine($header);

    /**
     * @return string
     */
    public function getBody();
}
