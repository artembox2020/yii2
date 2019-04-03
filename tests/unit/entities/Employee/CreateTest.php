<?php

namespace test\unit\entities\Employee;

use Codeception\Test\Unit;
use frontend\models\entities\Employee\Email;
use frontend\models\entities\Employee\Employee;
use frontend\models\entities\Employee\EmployeeId;
use frontend\models\entities\Employee\Events\EmployeeCreated;
use frontend\models\entities\Employee\Name;
use frontend\models\entities\Employee\Password;
use frontend\models\entities\Employee\Phone;

class CreateTest extends Unit
{
    public function testSuccessEmployeeCreate()
    {
        $employee = new Employee(
            $id = new EmployeeId(25),
            $name = new Name('Pupkin', 'Vasil', 'Petrovich'),
            $email = new Email('pupkin@gmail.com'),
            $password = new Password('32rsar23'),
            $phones = [
                new Phone(3, '063', '00000001'),
                new Phone(3, '063', '00000002')
            ]
        );

        $this->assertEquals($id, $employee->getId());
        $this->assertEquals($name, $employee->getName());
        $this->assertEquals($email, $employee->getEmail());
        $this->assertEquals($phones, $employee->getPhones());

        $this->assertNotNull($employee->getCreateDate());

        $this->assertTrue($employee->isActive());

        $this->assertCount(1, $statuses = $employee->getStatuses());
        $this->assertTrue(end($statuses)->isActive());

        $this->assertNotEmpty($events = $employee->releaseEvents());
        $this->assertInstanceOf(EmployeeCreated::class, end($events));
    }
}

//    public function testWithoutPhones()
//    {
//        $this->expectExceptionMessage('Employee must contain at least one phone.');
//
//        new Employee(
//            new EmployeeId(25),
//            new Name('Пупкин', 'Василий', 'Петрович'),
//            []
//        );
//    }
//
//    public function testWithSamePhoneNumbers()
//    {
//        $this->expectExceptionMessage('Phone already exists.');
//
//        new Employee(
//            new EmployeeId(25),
//            new Name('Пупкин', 'Василий', 'Петрович'),
//            new Address('Россия', 'Липецкая обл.', 'г. Пушкин', 'ул. Ленина', 25),
//            [
//                new Phone(7, '920', '00000001'),
//                new Phone(7, '920', '00000001'),
//            ]
//        );
//    }
//}
//
//class RenameTest extends Unit
//{
//    public function testSuccess()
//    {
//        $employee = EmployeeBuilder::instance()->build();
//
//        $employee->rename($name = new Name('New', 'Test', 'Name'));
//        $this->assertEquals($name, $employee->getName());
//
//        $this->assertNotEmpty($events = $employee->releaseEvents());
//        $this->assertInstanceOf(EmployeeRenamed::class, end($events));
//    }
//}
