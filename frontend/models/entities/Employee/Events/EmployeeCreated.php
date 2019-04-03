<?php

namespace frontend\models\entities\Employee\Events;

use frontend\models\entities\Employee\EmployeeId;

class EmployeeCreated
{
    public $employeeId;
    public function __construct(EmployeeId $employeeId)
    {
        $this->employeeId = $employeeId;
    }
}
