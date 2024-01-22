<?php

namespace Elavon\Converge2\DataObject\Resource;

use Elavon\Converge2\DataObject\Failure;

interface FailureInterface
{
    /**
     * @return bool
     */
    public function hasFailures();

    /**
     * @param string $name
     * @return bool
     */
    public function hasFailuresOnField($name);

    /**
     * @return array|null
     */
    public function getFailures();

    /**
     * @return Failure|null
     */
    public function getFirstFailure();

    /**
     * @return string
     */
    public function getFailuresAsJson();
}
