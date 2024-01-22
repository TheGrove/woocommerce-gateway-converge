<?php

namespace Elavon\Converge2\Request\Payload\Validation;

use Elavon\Converge2\Request\Payload\Validation\Constraint\BasicSafeString;
use Elavon\Converge2\Request\Payload\Validation\Constraint\ConstraintCollection;
use Elavon\Converge2\Request\Payload\Validation\Constraint\ConstraintCollectionInterface;
use Elavon\Converge2\Request\Payload\Validation\Constraint\ConstraintInterface;
use Elavon\Converge2\Request\Payload\Validation\Constraint\MaxLength;
use Elavon\Converge2\Request\Payload\Validation\Constraint\MinLength;
use Elavon\Converge2\Request\Payload\Validation\Constraint\PhoneSafeString;
use Elavon\Converge2\Request\Payload\Validation\Constraint\Required;
use Elavon\Converge2\Request\Payload\Validation\Constraint\Violation\DefaultViolationRenderer;
use Elavon\Converge2\Request\Payload\Validation\Constraint\Violation\ViolationInterface;
use Elavon\Converge2\Request\Payload\Validation\Constraint\Violation\ViolationRendererInterface;
use Elavon\Converge2\Schema\Converge2Schema;

abstract class AbstractValidator implements ValidatorInterface
{
    /** @var ViolationInterface[] */
    protected $violations = array();

    /** @var  ConstraintCollectionInterface */
    protected $constraintCollection;

    /** @var ViolationRendererInterface */
    protected $violationRenderer;

    public function __construct()
    {
        $this->constraintCollection = new ConstraintCollection();
    }

    public function setConstraintCollection(ConstraintCollectionInterface $constraintCollection)
    {
        $this->constraintCollection = $constraintCollection;
        return $this;
    }

    public function addConstraint(ConstraintInterface $constraint, $key = null)
    {
        $this->constraintCollection->add($constraint, $key);
        return $this;
    }

    public function removeConstraint($key = null)
    {
        $this->constraintCollection->remove($key);
        return $this;
    }

    public function getViolations()
    {
        return $this->violations;
    }

    public function hasViolations()
    {
        return !empty($this->violations);
    }

    public function getErrorMessages()
    {
        $renderer = $this->violationRenderer;

        if (!$renderer) {
            $renderer = new DefaultViolationRenderer();
        }

        $errors = array();

        foreach ($this->getViolations() as $violation) {
            $errors[] = $renderer->toString($violation);
        }

        return $errors;
    }

    public function setViolationRenderer(ViolationRendererInterface $renderer = null)
    {
        $this->violationRenderer = $renderer;
        return $this;
    }

    public function required($key = null, $errorMessageTemplate = '')
    {
        $this->addConstraint(new Required($errorMessageTemplate), $key);
        return $this;
    }

    public function maxLength($max, $key = null, $errorMessageTemplate = '')
    {
        $this->addConstraint(new MaxLength($max, $errorMessageTemplate), $key);
        return $this;
    }

    public function minLength($min, $key = null, $errorMessageTemplate = '')
    {
        $this->addConstraint(new MinLength($min, $errorMessageTemplate), $key);
        return $this;
    }

    public function commonConvergeMaxLength($key = null, $errorMessageTemplate = '')
    {
        return $this->maxLength(Converge2Schema::getInstance()->getCommonMaxLength(), $key, $errorMessageTemplate);
    }

    public function basicSafeString($key = null, $errorMessageTemplate = '')
    {
        $this->addConstraint(new BasicSafeString($errorMessageTemplate), $key);
        return $this;
    }

    public function phoneSafeString($key = null, $errorMessageTemplate = '')
    {
        $this->addConstraint(new PhoneSafeString($errorMessageTemplate), $key);
        return $this;
    }
}
