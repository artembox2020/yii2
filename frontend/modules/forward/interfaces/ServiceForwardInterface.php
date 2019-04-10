<?php

namespace frontend\modules\forward\interfaces;

/**
 * Отдает сущности по адресу
 *
 * Interface ServiceForwardInterface
 * @package frontend\modules\forward\interfaces
 */
interface ServiceForwardInterface
{
    /**
     * Получить ставф по адресу
     * @param $address
     */
    public function getStaff(string $address);
}
