<?php

namespace frontend\services\logger\src\service;

use frontend\services\custom\Debugger;
use frontend\services\logger\src\LoggerDtoInterface;
use frontend\services\logger\src\storage\StorageInterface;

/**
 * Class LoggerService
 * @package frontend\services\logger\src\service
 */
class LoggerService
{
    const MINUS_ONE = -1;
    private $storage;
    private $dto;
    private $_items = [];

    /**
     * LoggerService constructor.
     * @param StorageInterface $storage
     * @param LoggerDtoInterface $dto
     */
    public function __construct(StorageInterface $storage, LoggerDtoInterface $dto)
    {
        $this->storage = $storage;
        $this->dto = $dto;
    }

    /**
     * @param $data
     */
    public function createLog($data): void
    {
//        $dto = $this->dto->createDto($data);
//        Debugger::dd($dto);
        $this->storage->save($this->dto->createDto($data));
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        $this->loadItems();

        return $this->_items;
    }

    /**
     * Load Items
     */
    private function loadItems(): void
    {
        $this->_items = $this->storage->load();
    }
}
