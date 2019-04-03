<?php

namespace frontend\models\entities\Employee;

use Assert\Assertion;

class Email
{
    private $email;

    public function __construct(string $email)
    {
        Assertion::notEmpty($email);
        $this->email = $email;
    }
    public function getEmail(): string { return $this->email; }
}
