<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use frontend\storages\AddressStatStorage;

/**
 * Class AddressLoadingController
 * @package console\controllers
 */
class AddressLoadingController extends Controller
{
    /**
     * Generates all addresses average loadings into `address_load_data` table by the last days
     *
     * @param int $lastDays
     */
    public function actionMakeAverageAddressesLoadingByLastDays($lastDays)
    {
        $ass = new AddressStatStorage();
        Yii::$app->db->createCommand("DELETE FROM address_load_data")->execute();
        $ass->getAverageAddressesLoadingByLastDays($lastDays, 1);
    }
}