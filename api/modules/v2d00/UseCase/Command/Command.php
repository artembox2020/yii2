<?php

namespace api\modules\v2d00\UseCase\Command;

use frontend\services\custom\Debugger;
use Yii;

class Command
{
    const CONST_FALSE = 0;
    const CONST_TRUE = 1;

    /**
     * @param int $imei
     * @return bool|\yii\console\Response|\yii\web\Response
     * @throws \yii\db\Exception
     */
    public static function getCommand(int $imei)
    {
        if ($rows = (new \yii\db\Query())
            ->select(['action', 'unix_time_offset'])
            ->from('imei_action')
            ->where(['imei' => $imei])
            ->andWhere(['is_active' => self::CONST_TRUE])
            ->limit(self::CONST_TRUE)
            ->one()) {

            $returnData = [ 'type' => 'c', 'time' => $rows['unix_time_offset'], 'cmd' => $rows['action']];
            $response = Yii::$app->response;
            $response->format = \yii\web\Response::FORMAT_JSON;
            $response->data = $returnData;

            Yii::$app->db->createCommand()
                ->update('imei_action',
                    ['is_active' => self::CONST_FALSE],
                    ['imei' => $imei, 'is_active' => self::CONST_TRUE])
                ->execute();

            Yii::$app->response->statusCode = 206;
            return $response;
        }

        return null;

    }
}
