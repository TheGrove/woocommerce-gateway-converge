<?php

namespace Elavon\Converge2\Request\Payload\Validation\Constraint;

class PhoneSafeString extends SafeString
{
    const ID = 'phoneSafeString';
    const PATTERN = '/^[\w \-+:()\/]*$/';
    const ALLOWED = '()-+:_/';

    public function __construct($errorMessageTemplate = '')
    {
        parent::__construct(self::ID, self::PATTERN, $errorMessageTemplate);
    }
}