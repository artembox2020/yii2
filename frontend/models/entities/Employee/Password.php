<?php

namespace frontend\models\entities\Employee;

use Assert\Assertion;

class Password
{
    private $password;

    public function __construct(string $password)
    {
        Assertion::notEmpty($password);
        $this->password = $password;
    }
}
