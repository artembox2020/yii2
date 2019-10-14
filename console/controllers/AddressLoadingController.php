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
        $result = $this->prompt('Generate average addresses loadings by the last '.$lastDays.' days Y\N?');

        if (in_array($result, ['y', 'Y'])) {
            echo "Please wait...".PHP_EOL;
            $ass->getAverageAddressesLoadingByLastDays($lastDays, 1);
            echo "Done!".PHP_EOL;
        } else {
            echo 'Operation cancelled'.PHP_EOL;
        }
    }
}