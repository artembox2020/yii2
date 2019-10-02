<?php

namespace api\modules\v2d00\UseCase\Command;

use Yii;

class Command
{
    const CONST_FALSE = 0;
    const CONST_TRUE = 1;

    public static function getCommand(int $imei)
    {
        try {
            $rows = (new \yii\db\Query())
                ->select(['action', 'unix_time_offset'])
                ->from('imei_action')
                ->where(['imei' => $imei])
                ->andWhere(['is_active' => self::CONST_TRUE])
                ->limit(self::CONST_TRUE)
                ->one();

            $returnData = ['time' => $rows['unix_time_offset'], 'cmd' => $rows['action']];
            $response = Yii::$app->response;
            $response->format = \yii\web\Response::FORMAT_JSON;
            $response->data = $returnData;

            Yii::$app->db->createCommand()
                ->update('imei_action',
                    ['is_active' => self::CONST_FALSE],
                    ['imei' => $imei, 'is_active' => self::CONST_TRUE])
                ->execute();
        } catch (\Exception $exception) {
            Yii::$app->response->statusCode = 500;

            return $exception;
        }

        return $response;
    }
}
