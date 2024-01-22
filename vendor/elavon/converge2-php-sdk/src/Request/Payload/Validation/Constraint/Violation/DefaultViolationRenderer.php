<?php

namespace Elavon\Converge2\Request\Payload\Validation\Constraint\Violation;

class DefaultViolationRenderer implements ViolationRendererInterface
{
    protected $errorMessages = array();
    protected $parentPath;

    public function __construct($errorMessages = array(), $parentPath = '')
    {
        $this->errorMessages = $errorMessages;
        $this->parentPath = $parentPath;
    }

    public function toString(ViolationInterface $violation)
    {
        $key = trim(
            $this->parentPath . '.' . $violation->getField() . '.' . $violation->getConstraintId(),
            '.'
        );

        if (isset($this->errorMessages[$key])) {
            $violation->setMessage($this->errorMessages[$key]);
        }
        return $violation->getFormattedMessage();
    }
}
