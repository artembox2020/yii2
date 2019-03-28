<?php

namespace frontend\Employee;

class Employee
{
    public function getEmployee()
    {
        $employee = new Employee(
            new EmployeeId(25),
            new NikName('NikName'),
            new Name('Pupkin', 'Vasil', 'Petrovich'),
            new Email('pupkin@gmail.com'),
            new AuthKey('adsf'),
            new Password('asdf'),
            new Status(1),
            new CompanyId(1),
            new Ip(46.211.125.154),
        new CreatedAt()
        )
    }
}
