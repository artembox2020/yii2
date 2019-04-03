<?php

namespace frontend\models\entities;

use Assert\Assertion;

abstract class Id
{
    protected $id;

    public function __construct($id = null)
    {
        Assertion::notEmpty($id);
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }
    public function isEqualTo(self $other): bool
    {
        return $this->getId() === $other->getId();
    }
    public function __toString()
    {
        return (string)$this->id;
    }
}
