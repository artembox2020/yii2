<?php

namespace frontend\services\logger\src\storage;

interface StorageInterface
{
    public function load();
    public function save(array $array);
}
