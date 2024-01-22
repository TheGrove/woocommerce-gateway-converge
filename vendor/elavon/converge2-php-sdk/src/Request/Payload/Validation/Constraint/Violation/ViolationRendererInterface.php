<?php

namespace Elavon\Converge2\Request\Payload\Validation\Constraint\Violation;

interface ViolationRendererInterface
{
    /**
     * @param ViolationInterface $violation
     * @return string
     */
    public function toString(ViolationInterface $violation);
}