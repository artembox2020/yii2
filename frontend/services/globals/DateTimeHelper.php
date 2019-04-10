<?php

namespace frontend\services\globals;
use frontend\models\Jlog;

/**
 * Class DateTimeHelper
 * @package frontend\services\globals
 */
class DateTimeHelper
{
    const DAY_TIMESTAMP = 3600*24;

    /**
     * Gets the real timestamp by given timestamp
     * 
     * @param int $unixTimeOffset
     * @return int
     */
    public function getRealUnixTimeOffset(int $unixTimeOffset): int
    {
        if ($unixTimeOffset < self::DAY_TIMESTAMP) {

            return time() + Jlog::TYPE_TIME_OFFSET;
        }

        return $unixTimeOffset;
    }
}