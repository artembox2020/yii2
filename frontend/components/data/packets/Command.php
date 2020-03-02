<?php

namespace frontend\components\data\packets;

use Yii;
use yii\base\Component;
use frontend\models\CbEncashment;
use frontend\models\CbLogSearch;
use frontend\services\parser\CParser;
use api\modules\v2d00\controllers\JsonController;

/**
 * Imei commands parser into json format
 */
class Command extends Base {

    /**
     * Gets  last command packages json format by their incoming date
     * 
     * @param int $linesNumber
     * 
     * @return array
     */
    public function getLastLinesAsJson(int $linesNumber): array
    {
        $lines = (new \yii\db\Query())
            ->select(['CONVERT(action,UNSIGNED) AS action', 'unix_time_offset', 'created_at'])
            ->from('imei_action')
            ->where(['is_deleted' => false])
            ->andWhere(['!=', "action", 0])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit($linesNumber)
            ->all();

        $jsonLines = [];

        foreach ($lines as $line) {
            $jsonLines[$line['created_at']] = $this->convertCommandToJson($line);
        }

        return $jsonLines;
    }

    /**
     * Converts command package into json format
     * 
     * @param array $line
     * 
     * @return string
     */
    public function convertCommandToJson(array $line): string
    {
        $jsonData = [
            'type' => "c",
            'time' => $line['unix_time_offset'],
            'cmd' => $line['action']
        ];

        return json_encode($jsonData);
    }
}