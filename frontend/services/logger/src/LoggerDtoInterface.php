<?php

namespace frontend\services\logger\src;

interface LoggerDtoInterface
{
    public function createDto($data, $event);
}
