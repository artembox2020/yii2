<?php

namespace frontend\models\entities\Employee\Events;

use frontend\models\entities\Employee\EmployeeId;

/**
 * Class EmployeeReinstated
 * @package frontend\models\entities\Employee\Events
 */
class EmployeeReinstated
{
    public $employeeId;
    public $date;

    public function __construct(EmployeeId $employeeId, \DateTimeImmutable $date)
    {
        $this->employeeId = $employeeId;
        $this->date = $date;
    }
}
