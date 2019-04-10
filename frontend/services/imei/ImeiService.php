<?php

namespace frontend\services\imei;

use frontend\models\Imei;

/**
 * Class ImeiService
 * @package frontend\services\imei
 */
class ImeiService implements ImeiServiceInterface
{
    /**
     * @param $imei
     * @return Imei|\yii\db\ActiveQuery
     */
    public static function getImei($imei)
    {
        return $imei = Imei::find(['imei' => $imei]);
    }
}
