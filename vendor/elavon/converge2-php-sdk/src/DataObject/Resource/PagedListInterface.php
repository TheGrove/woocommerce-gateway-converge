<?php

namespace Elavon\Converge2\DataObject\Resource;

interface PagedListInterface
{
    /**
     * @return string|null
     */
    public function getHref();

    /**
     * @return string|null
     */
    public function getFirst();

    /**
     * @return string|null
     */
    public function getNext();

    /**
     * @return string|null
     */
    public function getPageToken();

    /**
     * @return string|null
     */
    public function getNextPageToken();

    /**
     * @return number|null
     */
    public function getSize();

    /**
     * @return number|null
     */
    public function getLimit();

    /**
     * @return array|null
     */
    public function getItems();
}