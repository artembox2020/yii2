<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use frontend\models\Jlog;
use frontend\models\Imei;
use yii\helpers\Console;

class JournalController extends Controller
{
    const ZERO = NULL;

    /**
     * Iitializes column `unix_time_offset` of `j_log` table
     * 
     * @param int $limit
     * @return int
     */
    public function actionInitUnixTimeOffset($limit)
    {
        $logItems = Jlog::find()->andWhere(['unix_time_offset' => self::ZERO])
                                ->orderBy(["STR_TO_DATE(date, '".Imei::MYSQL_DATE_TIME_FORMAT."')" => SORT_DESC])
                                ->limit($limit)
                                ->all();
        $counterInited = 0;

        foreach ($logItems as $item) {
            $date = str_replace(["\n","\r\n"], ["", ""], $item->date);

            $time = strtotime($date);

            if (!empty($date)) {
                $item->unix_time_offset = strtotime($date);
                $item->update();
                ++$counterInited;
            }
        }

        Console::output("Initialized items: {$counterInited}");

        return $counterInited;
    }

    /**
     * Iitializes column `unix_time_offset` of `j_log` table by iterations
     * 
     * @param int $limit
     * @param int $iters
     */
    public function actionInitUnixTimeOffsetByIterations($limit, $iters)
    {
        $counterTotalInited = 0;

        do {
            Console::output("{$iters} iterations left");
            $counterInited = $this->actionInitUnixTimeOffset($limit);
            $counterTotalInited += $counterInited;
            --$iters;
        }
        while ($iters > 0 && $counterInited > 0);

        Console::output("All iterations have been passed, total initialized items: {$counterTotalInited}");
    }
}