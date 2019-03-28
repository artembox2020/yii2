<?php

namespace frontend\services\logger\src\service;

use frontend\services\custom\Debugger;
use frontend\services\logger\src\LoggerDtoInterface;
use frontend\services\logger\src\storage\StorageInterface;

class LoggerService
{
    const MINUS_ONE = -1;
    private $storage;
    private $dto;
    private $_items = [];

    public function __construct(StorageInterface $storage, LoggerDtoInterface $dto)
    {
        $this->storage = $storage;
        $this->dto = $dto;
    }

    public function createLog($data)
    {
//        $dto = $this->dto->createDto($data);
//        Debugger::dd($dto);
        $this->storage->save($this->dto->createDto($data));
    }

    public function getItems()
    {
        $this->loadItems();

        return $this->_items;
    }

    private function loadItems()
    {
        $this->_items = $this->storage->load();
    }
}
