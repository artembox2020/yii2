<?php

namespace frontend\components;

use Yii;
use yii\base\Component;
use yii\helpers\Html;
use frontend\services\globals\Entity;
use yii\helpers\ArrayHelper;
use frontend\models\ImeiDataSearch;
use frontend\models\Imei;
use frontend\models\WmMashine;
use frontend\controllers\MonitoringController;

/**
 * Class MonitoringBuilder
 * @package frontend\components
 */
class MonitoringBuilder extends Component {
    private $monitoringController;
    public $layout;

    public const CONNECTION_IDLES_TIME = 1800;
    public const DATE_TIME_FORMAT = 'd.m.y H:i:s';

    /**
     * @inheritdoc
     */
    public function __construct($monitoringController = false)
    {
        $this->monitoringController = $monitoringController;
        $this->layout = Yii::$app->layout;
    }

    /**
     * Renders common data view
     * 
     * @param yii\data\ActiveDataProvider $dataProvider
     * @param frontend\models\ImeiDataSearch $searchModel
     * @param array $postParams
     * 
     * @return string
     */
    public function renderCommon($dataProvider, $searchModel, $postParams)
    {
        $data = $this->getData($dataProvider, $searchModel);

        return Yii::$app->view->render(
            "@frontend/views/monitoring/".$this->layout."/common",
            ['data' => $data, 'postParams' => $postParams]
        );
    }

    /**
     * Renders technical data view
     * 
     * @param yii\data\ActiveDataProvider $dataProvider
     * @param frontend\models\ImeiDataSearch $searchModel
     * @param array $postParams
     * 
     * @return string
     */
    public function renderTechnical($dataProvider, $searchModel, $postParams)
    {
        $data = $this->getData($dataProvider, $searchModel);

        return Yii::$app->view->render(
            "@frontend/views/monitoring/".$this->layout."/technical",
            ['data' => $data, 'postParams' => $postParams]
        );
    }

    /**
     * Renders financial data view
     * 
     * @param yii\data\ActiveDataProvider $dataProvider
     * @param frontend\models\ImeiDataSearch $searchModel
     * @param array $postParams
     * 
     * @return string
     */
    public function renderFinancial($dataProvider, $searchModel, $postParams)
    {
        $data = $this->getData($dataProvider, $searchModel);

        return Yii::$app->view->render(
            "@frontend/views/monitoring/".$this->layout."/financial",
            ['data' => $data, 'postParams' => $postParams]
        );
    }

    /**
     * Gets monitoring data
     * 
     * @param yii\data\ActiveDataProvider $dataProvider
     * @param frontend\models\ImeiDataSearch $searchModel
     * 
     * @return array
     */
    public function getData($dataProvider, $searchModel)
    {
        global $globalMonitoringData;
        
        if (!empty($globalMonitoringData)) {

            return $globalMonitoringData;
        }

        $controller = $this->monitoringController;
        $searchModel = new ImeiDataSearch();
        $data = [];

        $imeis = $dataProvider->query->all();
        foreach ($imeis as $imei) {
            $dProvider = $searchModel->searchImeiCardDataByImeiId($imei->id);
            $imeiData = $dProvider->query->one();
            $common = $this->getCommonData($imei);
            $financial = $this->getFinancialData($searchModel, $imei, $imeiData);
            $technical = $this->getTechnicalData($searchModel, $imei, $imeiData);
            $data[$imei->id] = ['common' => $common, 'financial' => $financial, 'technical' => $technical];
        }

        $globalMonitoringData = $data;

        return $data;
    }

    /**
     * Gets monitoring common data
     * 
     * @param frontend\models\Imei $imei
     * 
     * @return array
     */
    public function getCommonData($imei)
    {
        $address = $imei->fakeAddress;

        if (!$address) {

            return [];
        }

        $address->initSerialNumber();

        $balanceHolder = $imei->balanceHolder ?? $imei->getFakeBalanceHolder();
        $isDeleted  = $imei->balanceHolder ? false : true;

        $common = [
            'id' => $address->id,
            'name' => $address->name,
            'address' => $address->address,
            'floor' => $address->floor,
            'serialNumber' => $address->displaySerialNumber(),
            'imei' => $imei->imei,
            'bhId' => $balanceHolder->id,
            'bhName' => $balanceHolder->name,
            'is_deleted' => $isDeleted,
        ];

        return $common;
    }

    /**
     * Gets monitoring financial data
     * 
     * @param frontend\models\ImeiDataSearch $searchModel
     * @param frontend\models\Imei $imei
     * @param frontend\models\ImeiData $imeiData
     * 
     * @return array
     */
    public function getFinancialData($searchModel, $imei, $imeiData)
    {
        return [
            'in_banknotes' => $imeiData->in_banknotes,
            'fireproof_residue' => $imeiData->fireproof_residue,
            'money_in_banknotes' => $imeiData->money_in_banknotes,
            'last_encashment' => $searchModel->getScalarDateAndSumLastEncashmentByImeiId($imei->id),
            'pre_last_encashment' => $searchModel->getScalarDateAndSumPreLastEncashmentByImeiId($imei->id),
        ];
    }

    /**
     * Gets monitoring technical data
     * 
     * @param frontend\models\ImeiDataSearch $searchModel
     * @param frontend\models\Imei $imei
     * @param frontend\models\ImeiData $imeiData
     * 
     * @return array
     */
    public function getTechnicalData($searchModel, $imei, $imeiData)
    {
        $software = [
            'firmware_version_cpu' => $imei->firmware_version_cpu,
            'firmware_version' => $imei->firmware_version,
            'firmware_6lowpan' => $imei->firmware_6lowpan,
            'number_channel' => $imei->number_channel
        ];

        if (!empty($imei->capacity_bill_acceptance) && !empty($imeiData->in_banknotes)) {
            $fullness = (int)$imeiData->in_banknotes / (int)$imei->capacity_bill_acceptance;
            $fullness = $fullness * 100;
            $fullness = number_format($fullness, 2);
        } else {
            $fullness = 0;
        }
        $fullnessIndicator = 'green-icon.svg';

        $cpErrors = [1, 2,3, 4, 5, 6];
        $evtBillErrors = [1, 2, 3, 4, 4, 5, 6];
        $errorLabel = '';

        if (in_array($imeiData->packet, $cpErrors)) {
            $errorLabel = 'error';
        }

        $lastPingClass = $imei->getLastPingClass();

        if ($lastPingClass == 'ping-not-actual') {
            $fullnessIndicator = 'grey-icon.png';
        } elseif (in_array($imeiData->evt_bill_validator, $evtBillErrors)) {
            $fullnessIndicator = 'red-tab-icon.svg';
        }

        $terminal = [
            'last_ping' => $imei->getLastPing(),
            'last_ping_class' => $lastPingClass,
            'error' => $errorLabel,
            'last_ping_value' => date(self::DATE_TIME_FORMAT, $imei->getLastPingValue()),
            'level_signal' => $imeiData->getLevelSignal(),
            'phone_number' => $imei->phone_module_number,
            'money_in_banknotes' => Yii::$app->formatter->asDecimal($imei->getOnModemAccount(), 0),
            'fullness' => $fullness,
            'fullnessIndicator' => $fullnessIndicator,
            'in_banknotes' => $imeiData->in_banknotes,
            'imei' => $imei->imei,
            'traffic' => $imei->traffic
        ];

        $devices = $this->getDevicesData($searchModel, $imei);

        $technical = ['software' => $software, 'terminal' => $terminal, 'devices' => $devices];

        return $technical;
    }

    /** 
     * Gets Wm mashines data
     * 
     * @param \frontend\models\ImeiDataSearch $searchModel
     * @param \frontend\models\Imei $imei
     * 
     * @return array
     */
    public static function getDevicesData($searchModel, $imei)
    {
        $devices = [];

        if (empty($imei)) {

            return $devices;
        }

        $dataProviderWmMashine = $searchModel->searchWmMashinesByImeiId($imei->id);

        $mashines = $dataProviderWmMashine->query->all();

        foreach ($mashines as $model) {
            $indicator = 'ping-actual';
            $connectionIdleStates = [0, 16];
            $errorStates = [9, 10, 11, 12, 13, 14, 21, 25];

            if (in_array($model->current_status, $connectionIdleStates) || (time() - $model->ping > self::CONNECTION_IDLES_TIME)) {
                $indicator = 'ping-not-actual';
            } elseif (in_array($model->current_status, $errorStates)) {
                $indicator = 'color-red';
            }

            $lastPing = Yii::$app->formatter->asDate($model->ping, WmMashine::PHP_DATE_TIME_FORMAT);
            $timeParts = explode(" ", $lastPing);

            $deviceItem = [
                'type' => $model->type_mashine,
                'number_device' => $model->number_device,
                'level_signal' => $model->level_signal,
                'id' => $model->id,
                'bill_cash' => $model->bill_cash,
                'current_status' => Yii::t('frontend', $model->getState()),
                'no_connection' => $indicator == 'ping-not-actual' ? 'no-connection' : '',
                'indicator' => $indicator,
                'display' => $model->display,
                'last_ping' => $lastPing,
                'money_in_banknotes' =>  \Yii::$app->formatter->asDecimal($model->bill_cash, 0),
            ];

            $devices[$model->number_device] = $deviceItem;
        }

        return $devices;
    }

    /**
     * Sets header class depending on post data
     * 
     * @param array $postParams
     * @param string $name
     * 
     * @return string
     */
    public static function setHeaderClass($postParams, $name)
    {
        $isEmpty = empty($postParams) || empty($postParams['sortOrder']);
        switch ($name) {
            case 'number':

                return $isEmpty ? 'dropup' : 
                       ($postParams['sortOrder'] != MonitoringController::SORT_BY_SERIAL ? 'dropdown' : 'dropup');
            case 'bhname':

                return $isEmpty ? 'dropup' : 
                       ($postParams['sortOrder'] != MonitoringController::SORT_BY_BALANCEHOLDER ? 'dropdown' : 'dropup');
            
            case 'address':

                return $isEmpty ? 'dropup' : 
                       ($postParams['sortOrder'] != MonitoringController::SORT_BY_ADDRESS ? 'dropdown' : 'dropup');
        }
    }
}