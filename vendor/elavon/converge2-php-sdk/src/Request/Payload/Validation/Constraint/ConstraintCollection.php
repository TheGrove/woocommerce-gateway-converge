<?php

namespace Elavon\Converge2\Request\Payload\Validation\Constraint;

class ConstraintCollection implements ConstraintCollectionInterface
{
    protected $constraintArray = array();

    public function add(ConstraintInterface $constraint, $key = null)
    {
        if (isset($key)) {
            if (isset($this->constraintArray[$key])) {
                if (!is_array($this->constraintArray[$key])) {
                    $this->constraintArray[$key] = array($this->constraintArray[$key]);
                }
                $this->constraintArray[$key][] = $constraint;
            } else {
                $this->constraintArray[$key] = $constraint;
            }

        } else {
            $this->constraintArray[] = $constraint;
        }

        return $this;
    }

    public function remove($key = null)
    {
        if (isset($key)) {
            unset($this->constraintArray[$key]);
        } else {
            array_pop($this->constraintArray);
        }

        return $this;
    }

    public function getConstraintArray()
    {
        return $this->constraintArray;
    }
}