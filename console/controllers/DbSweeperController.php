<?php

namespace console\controllers;

use Yii;
use yii\helpers\Console;

/**
 * Class DbSweeperController
 * @package console\controllers
 */
class DbSweeperController extends \yii\console\Controller
{
    /**
     * Purges state tables (`imei_data`, `wm_mashine_data`, `j_log`, `wm_log`, `cb_log`) by start and final timestamps 
     *
     * @param int $start
     * @param int $end
     */
    public function actionPurgeByTimestamps($start, $end)
    {
        $queryImeiData = "DELETE FROM imei_data WHERE created_at BETWEEN :start AND :end";
        $queryJlog = "DELETE FROM j_log WHERE unix_time_offset BETWEEN :start AND :end";
        $queryWmMashineData = "DELETE FROM wm_mashine_data WHERE created_at BETWEEN :start AND :end";
        $queryWmLog = "DELETE FROM wm_log WHERE created_at BETWEEN :start AND :end";
        $queryCbLog = "DELETE FROM cb_log WHERE created_at BETWEEN :start AND :end";

        $bindValues = [':start' => $start, ':end' => $end];
        $dateStart = Yii::$app->formatter->format(date("Y-m-d", $start), 'date');
        $dateEnd = Yii::$app->formatter->format(date("Y-m-d", $end), 'date');

        $result = $this->prompt(
            "Do you really want permanently delete all state data between `{$dateStart}` and `{$dateEnd}` Y\N?"
        );

        if (in_array($result, ['y', 'Y'])) {
            echo "Data is being purged, please wait...".PHP_EOL;

            // delete imei_data packets
            Yii::$app->db->createCommand($queryImeiData)->bindValues($bindValues)->execute();
            echo "> `imei_data` table has been purged".PHP_EOL;

            // delete j_log packets
            Yii::$app->db->createCommand($queryJlog)->bindValues($bindValues)->execute();
            echo "> `j_log` table has been purged".PHP_EOL;

            // delete wm_mashine_data packets
            Yii::$app->db->createCommand($queryWmMashineData)->bindValues($bindValues)->execute();
            echo "> `wm_mashine_data` table has been purged".PHP_EOL;

            // delete wm_log packets
            Yii::$app->db->createCommand($queryWmLog)->bindValues($bindValues)->execute();
            echo "> `wm_log` table has been purged".PHP_EOL;

            // delete cb_log packets
            Yii::$app->db->createCommand($queryCbLog)->bindValues($bindValues)->execute();
            echo "> `cb_log` table has been purged".PHP_EOL;
        } else {
            echo 'Operation cancelled'.PHP_EOL;
        }
    }
}
