<?php

namespace Elavon\Converge2\Request\Payload\Validation\Constraint;

abstract class AbstractConstraint implements ConstraintInterface
{
    /** @var string */
    protected $id;

    /**
     * @var string
     */
    protected $errorMessageTemplate = 'Constraint violated';

    public function __construct($errorMessageTemplate = '')
    {
        if ($errorMessageTemplate) {
            $this->errorMessageTemplate = $errorMessageTemplate;
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getErrorMessageTemplate()
    {
        return $this->errorMessageTemplate;
    }
}
