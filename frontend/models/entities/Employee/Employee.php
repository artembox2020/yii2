<?php

namespace frontend\models\entities\Employee;

use frontend\models\entities\AggregateRootInterface;
use frontend\models\entities\EventTrait;

class Employee implements AggregateRootInterface
{
    use EventTrait;

    private $id;
    private $name;
    private $email;
    private $password;
    private $phones = [];
    private $createDate;
    private $statuses = [];

    public function __construct(
        EmployeeId $id, Name $name, Email $email, Password $password, array $phones
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->phones = new Phones($phones);
        $this->createDate = new \DateTimeImmutable();
        $this->addStatus(Status::ACTIVE, $this->createDate);
        $this->recordEvent(new Events\EmployeeCreated($this->id));
    }

    public function rename(Name $name): void {  }

    public function changeEmail(Email $email): void {  }

    public function changePassword(Password $password): void {  }

    public function addPhone(Phone $phone): void {  }

    public function removePhone($index): void {  }

    public function archive(\DateTimeImmutable $date): void {  }

    public function reinstate(\DateTimeImmutable $date): void
    {
        if (!$this->isArchived()) {
            throw new \DomainException('Employee is not archived.');
        }
        $this->addStatus(Status::ACTIVE, $date);
        $this->recordEvent(new Events\EmployeeReinstated($this->id, $date));
    }

    public function remove(): void {  }

    public function isActive(): bool
    {
        return $this->getCurrentStatus()->isActive();
    }

    private function getCurrentStatus(): Status
    {
        return end($this->statuses);
    }

    private function addStatus($value, \DateTimeImmutable $date): void
    {
        $this->statuses[] = new Status($value, $date);
    }

    public function getId(): EmployeeId { return $this->id; }
    public function getName(): Name { return $this->name; }
    public function getPhones(): array { return $this->phones->getAll(); }
    public function getEmail(): Email { return $this->email; }
    public function getCreateDate(): \DateTimeImmutable { return $this->createDate; }
    public function getStatuses(): array { return $this->statuses; }
}
