<?php

namespace frontend\models\AppLogger;

/**
 * Interface AppLoggerInterface
 * @package frontend\models\AppLogger
 */
interface AppLoggerInterface
{
    /**
     * @param $event
     * @param $message
     */
    public function log($event, $message);
}
