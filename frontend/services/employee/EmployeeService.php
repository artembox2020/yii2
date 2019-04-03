<?php

namespace frontend\service\employee;

class EmployeeService
{
    private $employees;
    private $dispatcher;

    public function __construct(EmployeeRepository $employees, EventDispatcher $dispatcher)
    {
        $this->employees = $employees;
        $this->dispatcher = $dispatcher;
    }
}
