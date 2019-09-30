<?php

namespace api\modules\v2d00\UseCase\Command;

use Yii;

class Command
{
    public static function getCommand(int $imei)
    {
        $rows = (new \yii\db\Query())
            ->select(['action', 'unix_time_offset'])
            ->from('imei_action')
            ->where(['imei' => $imei])
            ->andWhere(['is_active' => 1])
            ->limit(1)
            ->one();

        $returnData = ['time' => $rows['unix_time_offset'], 'cmd' => $rows['action']];
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = $returnData;

        Yii::$app->db->createCommand()
            ->update('imei_action', ['is_active' => 0], ['imei' => $imei, 'is_active' => 1])
            ->execute();

        return $response;
    }
}
