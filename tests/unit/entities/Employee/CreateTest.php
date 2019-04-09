<?php

namespace test\unit\entities\Employee;

use Codeception\Test\Unit;
use common\models\User;
use PHPUnit\Framework\TestCase;

class CreateTest extends TestCase
{
//    public function testSuccessEmployeeCreate()
//    {
//        $user = new User();
//
//        $user->username = 'UserName';
//        $user->email = 'adsf@com.com';
//
//        $this->assertEquals($user->username, $user->username);
//        $this->assertEquals($name, $employee->getName());
//        $this->assertEquals($email, $employee->getEmail());
//        $this->assertEquals($phones, $employee->getPhones());
//
//        $this->assertNotNull($employee->getCreateDate());
//
//        $this->assertTrue($employee->isActive());
//
//        $this->assertCount(1, $statuses = $employee->getStatuses());
//        $this->assertTrue(end($statuses)->isActive());
//
//        $this->assertNotEmpty($events = $employee->releaseEvents());
//        $this->assertInstanceOf(EmployeeCreated::class, end($events));
//    }

    public function testValidateUser()
    {
        $user = new User();
        $user->username = 'Name';
        $user->email = 'asdf@asdf.com';
        $user->status = User::STATUS_ACTIVE;


        $this->assertFalse($user->validate(), 'validate true');
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
