<?php

namespace frontend\services\logger\src\storage;

/**
 * Interface StorageInterface
 * @package frontend\services\logger\src\storage
 */
interface StorageInterface
{
    public function load();
    public function save(array $array);
}
