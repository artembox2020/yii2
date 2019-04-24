<?php

namespace frontend\models;

use common\models\User;
use DateTime;
use frontend\services\custom\Debugger;
use frontend\services\globals\Entity;
use frontend\services\globals\EntityHelper;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii2tech\ar\softdelete\SoftDeleteBehavior;
use yii\web\view;

/**
 * This is the model class for table "wm_mashine".
 *
 * @property int $id
 * @property int $imei_id
 * @property int $company_id
 * @property int $balance_holder_id
 * @property int $address_id
 * @property string $type_mashine
 * @property int $number_device
 * @property string $serial_number
 * @property int $level_signal
 * @property int $bill_cash
 * @property int $door_position
 * @property int $door_block_led
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $is_deleted
 * @property int $deleted_at
 * @property int $current_status
 * @property string $model
 * @property string $brand
 * @property integer $date_install
 * @property integer $date_build
 * @property integer $date_purchase
 * @property integer $date_connection_monitoring
 * @property string $display
 * @property int $ping
 * @property int $inventory_number
 *
 * @property Imei $imei
 */
class WmMashine extends \yii\db\ActiveRecord
{
    const STATUS_OFF = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_UNDER_REPAIR = 2;
    const STATUS_JUNK = 3;
    const ONE = 1;
    const TWO = 2;
    const THREE = 3;
    const FOUR = 4;
    const FIVE = 5;

    const DATE_TIME_FORMAT = 'H:i d.m.Y';

    const PHP_DATE_TIME_FORMAT = 'php:H:i d.m.Y';
    
    const LEVEL_SIGNAL_MAX = 10000;

    const STATUS_DISCONNECTED = 0;
    
    const TYPE_IDLES_OK = 0;
    const TYPE_CP_ERROR = 1;
    const MASHINE_ERROR = 2;

    private $wm;

    /** @var array current state */
    public $current_state = [
        '-2' => 'nulling',
        '-1' => 'refill',
        'disconnected',
        'idle',
        'power on',
        'busy',
        'washing',
        'rising',
        'extraction',
        'waiting door',
        'end cycle',
        'freeze mode',
        '1e water sensor',
        '3e motor sensor',
        '4e water supply',
        '5e problem plum',
        '8e motor',
        '9e uc poser supply',
        'ae communication',
        'de switch',
        'ce cooling',
        'de unclosed door',
        'fe ventilation',
        'he heater',
        'le water leak',
        'oe of overflow',
        'te temp sensor',
        'ue loading cloth',
        'max error',
        'not connect to WM'
    ];

    /** @var array
     * Code for log wm
     */
    public $log_state = [
        '-19' => 'ERROR_UE',
        '-18' => 'ERROR_tE',
        '-17' => 'ERROR_OE_OF',
        '-16' => 'ERROR_LE',
        '-15' => 'ERROR_HE',
        '-14' => 'ERROR_FE',
        '-13' => 'ERROR_dE',
        '-12' => 'ERROR_CE',
        '-11' => 'ERROR_bE',
        '-10' => 'ERROR_AE',
        '-9' => 'ERROR_9E_Uc',
        '-8' => 'ERROR_8E',
        '-7' => 'ERROR_5E',
        '-6' => 'ERROR_4E',
        '-5' => 'ERROR_3E',
        '-4' => 'ERROR_1E',
        '-3' => 'ZERO_WORK',
        '-2' => 'FREEZE_WITH_WATER',
        '-1' => 'NO_CONNECT_MCD',
        '0' => 'NO_POWER',
        '1' => 'POWER_ON_WASHER',
        '2' => 'REFILL_WASHER',
        '3' => 'WASHING_DRESS',
        '4' => 'RISING_DRESS',
        '5' => 'EXTRACTION_DRESS',
        '6' => 'WASHING_END',
        '7' => 'WASHER_FREE',
        '8' => 'NULLING_WASHER',
        '9' => 'CONNECT_MCD',
        '10' => 'SUB_BY_WORK',
        '11' => 'MAX_WASHER_EVENT',
    ];

    /** @var array washing_mode code */
    public $washing_mode = [
        '-1' => 'IDLEWASH',
        '0' => 'COTTON',
        '1' => 'SYNTHETICS',
        '2' => 'HANDHELD_WOOL',
        '3' => 'FAST_CHILD',
        '4' => 'RISING_EXTRAC',
        '5' => 'EXTRAC',
        '6' => 'OUTERWEAR',
        '7' => 'INTENSIVE',
        '8' => 'DELAYED_WASHING',
    ];

    /** @var array wash temperature code */
    public $wash_temperature = [
        '-1' => 'NOLEDTEMP',
        '0' => 'TEMP30',
        '1' => 'TEMP40',
        '2' => 'TEMP60',
        '3' => 'TEMP95',
    ];

    /** @var array spine type code */
    public $spin_type = [
        '-1' => 'NOLEDEXTRAC',
        '0' => 'NOEXTRAC',
        '1' => 'EXTRAC400',
        '2' => 'EXTRAC800',
        '3' => 'EXTRAC800_PLUS',
    ];

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'softDeleteBehavior' => [
                'class' => SoftDeleteBehavior::className(),
                'softDeleteAttributeValues' => [
                    'is_deleted' => true,
                    'deleted_at' => time() + Jlog::TYPE_TIME_OFFSET
                ],
            ],
            [
                'class' => TimestampBehavior::className(),
                'value' => time() + Jlog::TYPE_TIME_OFFSET
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wm_mashine';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['date_install', 'filter', 'filter' => 'strtotime', 'skipOnEmpty' => true],
            ['date_build', 'filter', 'filter' => 'strtotime', 'skipOnEmpty' => true],
            ['date_purchase', 'filter', 'filter' => 'strtotime', 'skipOnEmpty' => true],
            ['date_connection_monitoring', 'filter', 'filter' => 'strtotime', 'skipOnEmpty' => true],
            [['imei_id', 'status', 'company_id', 'balance_holder_id', 'address_id', 'number_device', 'inventory_number', 'serial_number'], 'required'],
            [['serial_number'], 'unique'],
            [['serial_number'], 'unique', 'targetAttribute' => ['serial_number']],
            ['inventory_number', 'validateInventoryNumberDevice', 'skipOnEmpty' => true, 'skipOnError' => false,
                'message' => \Yii::t('frontend', 'This Inventory number has already been taken')],
            [['imei_id', 'number_device',
                'level_signal',
                'bill_cash',
                'door_position',
                'door_block_led',
                'status',
                'current_status',
                'created_at',
                'updated_at',
                'deleted_at',
            ], 'integer'],
            [['type_mashine', 'serial_number', 'model', 'brand'], 'string', 'max' => 255],
            ['status', 'in', 'range' => array_keys(self::statuses())],
            [['imei_id'], 'exist', 'skipOnError' => true, 'targetClass' => Imei::className(), 'targetAttribute' => ['imei_id' => 'id']],
            [['display'], 'string' , 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('frontend', 'ID'),
            'imei_id' => Yii::t('frontend', 'Imei ID'),
            'serial_number' => Yii::t('frontend', 'Serial number'),
            'company_id' => Yii::t('frontend', 'Company'),
            'address_id' => Yii::t('frontend', 'Address'),
            'type_mashine' => Yii::t('frontend', 'Type Mashine'),
            'number_device' => Yii::t('frontend', 'Number Device'),
            'level_signal' => Yii::t('frontend', 'Level Signal'),
            'bill_cash' => Yii::t('frontend', 'Bill Cash'),
            'door_position' => Yii::t('frontend', 'Door Position'),
            'door_block_led' => Yii::t('frontend', 'Door Block Led'),
            'status' => Yii::t('frontend', 'Status'),
            'current_status' => Yii::t('frontend', 'Current Status'),
            'created_at' => Yii::t('frontend', 'Created At'),
            'updated_at' => Yii::t('frontend', 'Updated At'),
            'is_deleted' => Yii::t('frontend', 'Is Deleted'),
            'deleted_at' => Yii::t('frontend', 'Deleted At'),
            'brand' => Yii::t('frontend', 'Brand'),
            'model' => Yii::t('frontend', 'Model'),
            'date_install' => Yii::t('frontend', 'Date Install'),
            'date_build' => Yii::t('frontend', 'Date build'),
            'date_purchase' => Yii::t('frontend', 'Date Purchase'),
            'date_connection_monitoring' => Yii::t('frontend', 'Date connection to monitoring'),
            'display' => Yii::t('frontend' ,'Display'),
            'ping' => Yii::t('frontend', 'Ping'),
            'inventory_number' => Yii::t('frontend', 'Inventory number'),
        ];
    }


    /**
     * @inheritdoc
     */
    public function getModel()
    {
        if (!$this->wm) {
            $this->wm = new WmMashine();
        }

        return $this->wm;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImei()
    {
        return $this->hasOne(Imei::className(), ['id' => 'imei_id']);
    }

    /**
     * Returns imei statuses list
     *
     * @param mixed $status
     * @return array|mixed
     */
    public static function statuses($status = null)
    {
        $statuses = [
            self::STATUS_OFF => Yii::t('frontend', 'Disabled'),
            self::STATUS_ACTIVE => Yii::t('frontend', 'Active'),
            self::STATUS_UNDER_REPAIR => Yii::t('frontend', 'Under repair'),
            self::STATUS_JUNK => Yii::t('frontend', 'Junk'),
        ];

        if ($status === null) {
            return $statuses;
        }

        return $statuses[$status];
    }

    /**
     * @return $this|\yii\db\ActiveQuery
     */
    public static function find()
    {
        return parent::find()->where(['wm_mashine.is_deleted' => false]);
//        return new UserQuery(get_called_class());
//        return parent::find()->where(['is_deleted' => 'false'])
//            ->andWhere(['status' => Imei::STATUS_ACTIVE]);
//            ->andWhere(['<', '{{%user}}.created_at', time()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public static function getStatusOff()
    {
        return WmMashine::find()
            ->where(['status' => WmMashine::STATUS_OFF])
            ->orWhere(['status' => WmMashine::STATUS_JUNK])
            ->orWhere(['status' => WmMashine::STATUS_UNDER_REPAIR])
            ->all();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddress()
    {
        return $this->hasOne(AddressBalanceHolder::className(), ['id' => 'address_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBalanceHolder()
    {
        return $this->hasOne(BalanceHolder::className(), ['id' => 'balance_holder_id']);
    }

    /**
     * @param $attribute
     * @throws \yii\web\NotFoundHttpException
     */
    public function validateInventoryNumberDevice($attribute)
    {
        if ($this->isNewRecord) {
        $array = [];
        $entity = new Entity();

        if (WmMashine::find()->all()) {
            $result = $entity->getUnitsPertainCompany(new WmMashine());

            foreach ($result as $value) {
                    $array[] = $value->inventory_number;
            }

            if (in_array($this->$attribute, $array)) {
                        $this->addError($attribute, Yii::t('frontend', 'This Inventory number has already been taken'));
                    }
        }
        }
    }

    /**
     * Gets current state of the machine
     * 
     * @return string|null
     */
    public function getState()
    {
        if (array_key_exists($this->current_status, $this->current_state)) {

            return $this->current_state[$this->current_status];
        }

        return null;
    }

    /**
     * Gets mashine query by imei id
     * 
     * @param int $imeiId
     * @return ActiveQuery
     */
    public static function getMachinesQueryByImeiId($imeiId)
    {
        $query = self::find()->andWhere(['wm_mashine.imei_id' => $imeiId, 'wm_mashine.status' => self::STATUS_ACTIVE]);

        return $query;
    }

    /**
     * Gets state view
     * 
     * @return string
     */
    public function getStateView()
    {
        $viewObject = new View();

        return $viewObject->render('/wm-mashine/stateView', ['mashine' => $this]);
    }

    /**
     * Gets level signal
     * 
     * @return null|int
     */
    public function getLevelSignal()
    {
        if (is_null($this->level_signal) || $this->level_signal > self::LEVEL_SIGNAL_MAX) {

            return null;
        }

        return $this->level_signal;
    }

    /**
     * @return integer
     */
    public function getGeneralCount()
    {
        $entity = new Entity();
        $query = WmMashine::find();
        $query = $query->andWhere(['company_id' => $entity->getCompanyId()]);

        return $query->count();
    }

    /**
     * @param $modelName
     * @return int|string
     */
    public function getModelNameCount($modelName)
    {
        $entity = new Entity();
        $query = WmMashine::find();
        $query = $query->andWhere(['company_id' => $entity->getCompanyId(), 'model' => $modelName]);

        return $query->count();
    }

    /**
     * @return int|string
     */
    public function getStockCountAll()
    {
        $entity = new Entity();
        $query = WmMashine::find();
        $query = $query->andWhere(['company_id' => $entity->getCompanyId(), 'status' => self::STATUS_OFF]);

        return $query->count();
    }

    /**
     * @return int|string
     */
    public function getActiveCountAll()
    {
        $entity = new Entity();
        $query = WmMashine::find();
        $query = $query->andWhere(['company_id' => $entity->getCompanyId(), 'status' => self::STATUS_ACTIVE]);

        return $query->count();
    }

    /**
     * @return int|string
     */
    public function getRepairCountAll()
    {
        $entity = new Entity();
        $query = WmMashine::find();
        $query = $query->andWhere(['company_id' => $entity->getCompanyId(), 'status' => self::STATUS_UNDER_REPAIR]);

        return $query->count();
    }

    /**
     * @return int|string
     */
    public function getJunkCountAll()
    {
        $entity = new Entity();
        $query = WmMashine::find();
        $query = $query->andWhere(['company_id' => $entity->getCompanyId(), 'status' => self::STATUS_JUNK]);

        return $query->count();
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     * @throws \yii\web\NotFoundHttpException
     */
    public function getWmAll()
    {
        $mashine = new Entity();

        return $mashine->getUnitsPertainCompany(new WmMashine());
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getModelWm()
    {
        $user = User::findOne(Yii::$app->user->id);
        $result = WmMashine::find()
            ->select(['model'], 'DISTINCT')
            ->andWhere(['company_id' => $user->company_id])->all();

        return $result;
    }

    /**
     * @return int
     * @throws \yii\web\NotFoundHttpException
     */
    public function getUpTo1Year()
    {
        $array = [];

        foreach ($this->getWmAll() as $item) {
            if ($this->getUpToYear($item->date_build) <= self::ONE and
                $this->getUpToYear($item->date_build) < self::TWO) {
                $array[] = $item;
            }
        }

        return count($array);
    }

    /**
     * @return int
     * @throws \yii\web\NotFoundHttpException
     */
    public function getUpTo2Year()
    {
        $array = [];

        foreach ($this->getWmAll() as $item) {
            if ($this->getUpToYear($item->date_build) <= self::TWO and
                $this->getUpToYear($item->date_build) > self::ONE) {
                $array[] = $item;
            }
        }

        return count($array);
    }

    /**
     * @return int
     * @throws \yii\web\NotFoundHttpException
     */
    public function getUpTo3Year()
    {
        $array = [];

        foreach ($this->getWmAll() as $item) {
            if ($this->getUpToYear($item->date_build) <= self::THREE and
                $this->getUpToYear($item->date_build) > self::TWO) {
                $array[] = $item;
            }
        }

        return count($array);
    }

    /**
     * @return int
     * @throws \yii\web\NotFoundHttpException
     */
    public function getUpTo4Year()
    {
        $array = [];

        foreach ($this->getWmAll() as $item) {
            if ($this->getUpToYear($item->date_build) <= self::FOUR and
                $this->getUpToYear($item->date_build) > self::THREE) {
                $array[] = $item;
            }
        }

        return count($array);
    }

    /**
     * @return int
     * @throws \yii\web\NotFoundHttpException
     */
    public function getUpTo5Year()
    {
        $array = [];

        foreach ($this->getWmAll() as $item) {
            if ($this->getUpToYear($item->date_build) <= self::FIVE and
                $this->getUpToYear($item->date_build) > self::FOUR) {
                $array[] = $item;
            }
        }

        return count($array);
    }

    /**
     * @return int
     * @throws \yii\web\NotFoundHttpException
     */
    public function getUp5Year()
    {
        $array = [];

        foreach ($this->getWmAll() as $item) {
            if ($this->getUpToYear($item->date_build) > self::FIVE) {
                $array[] = $item;
            }
        }

        return count($array);
    }

    /**
     * @param $date
     * @return string
     */
    public function getUpToYear($date)
    {
        $st = date('Y');
        $date = date('Y', $date);
        if (isset($date)) {
            $res = $this->dateDifference($date, $st);
            return $res;
        }
        $res = '';

        return $res;
    }

    /**
     * @param $date_1
     * @param $date_2
     * @return mixed
     */
    public function dateDifference($date_1, $date_2)
    {
        $diff = $date_2 - $date_1;
        return $diff;
    }

    /**
     * Returns last ping date time, formatted
     * 
     * @return string
     */
    public function getLastPing()
    {
        $actualityClass = $this->getActualityClass();
        $formattedDate = Yii::$app->formatter->asDate($this->ping, self::PHP_DATE_TIME_FORMAT);

        return "<span class='$actualityClass'>".$formattedDate."</span>";
    }

    /**
     * Returns display value view
     * 
     * @return string
     */
    public function getOnDisplay()
    {
        $actualityClass = $this->getActualityClass();

        return "<span class='$actualityClass'>".Yii::t('frontend', 'On Display:').' '.$this->display."</span>";
    }

    /**
     * Returns whether ping is actual
     * 
     * @return string
     */
    public function getActualityClass()
    {
        $actualityClass = 'ping-not-actual';
        $halfHourBeforeTimestamp = strtotime("-30 minutes") + Jlog::TYPE_TIME_OFFSET;

        if ($this->ping >= $halfHourBeforeTimestamp && $this->current_status != self::STATUS_DISCONNECTED) {
            $actualityClass = 'ping-actual';
        }

        return $actualityClass;
    }

    /**
     * Sets last ping value if necessary
     * 
     * @param WmMashineData $wm_mashine_data
     */
    public function setLastPing(WmMashineData $wm_mashine_data)
    {
        if (
            $wm_mashine_data->current_status != self::STATUS_DISCONNECTED
            || 
            $this->current_status != self::STATUS_DISCONNECTED
        ) {
            $this->ping = $wm_mashine_data->ping;
        }
    }

    /**
     * @return bool
     */
    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {

            return false;
        }

        $dbHelper = Yii::$app->dbCommandHelperOptimizer;
        $className = str_replace(["\\"], ["/"], self::className());
        $dbHelper->deleteUnitTempByEntityId($className, 'idleHoursReasons', $this->id);

        return true;
    }

    /**
     * Gets if Central Board idles
     * 
     * @param int $timestamp
     * @param int $endTimestamp
     * @param Imei $imei
     * 
     * @return bool
     */
    public function getIfCpIdles($timestamp, $endTimestamp, $imei)
    {
        $dbHelper = Yii::$app->dbCommandHelperOptimizer;
        $cpErrors = '1,2,3,4,5,6';
        $queryCpErrorCondition = "AND packet IN (".$cpErrors.")";
        $dbHelper->getBaseUnitQueryByTimestamps($timestamp, $endTimestamp, new ImeiData(), $imei, 'imei_id', 'created_at, imei_id');
        $dbHelper->addQueryString($queryCpErrorCondition);

        return $dbHelper->getCount() ? false : true;
    }

    /**
     * Gets if Mashine Connection idles
     * 
     * @param int $timestamp
     * @param int $endTimestamp
     * @param Imei $imei
     * 
     * @return bool
     */
    public function getIfMashineConnectionIdles($timestamp, $endTimestamp, $imei)
    {
        $dbHelper = Yii::$app->dbCommandHelperOptimizer;
        $connectionErrors = '0, 16';
        $queryEvtBillErrorCondition = "AND current_status IN (".$connectionErrors.")";
        $dbHelper->getBaseUnitQueryByTimestamps($timestamp, $endTimestamp, new WmMashineData(), $imei, 'mashine_id', 'created_at, imei_id');
        $dbHelper->addQueryString($queryEvtBillErrorCondition);

        return $dbHelper->getCount() ? false : true;
    }

    /**
     * Gets whether has no idling
     * 
     * @param int $timestamp
     * @param int $endTimestamp
     * @param Imei $imei
     * 
     * @return bool
     */
    public function getIfIdlesOk($timestamp, $endTimestamp, $imei)
    {
        $dbHelper = Yii::$app->dbCommandHelperOptimizer;
        $evtBillErrors = '1,2,3,4,5,6';
        $cpErrors = '1,2,3,4,5,6';
        $wmMashineConnectionErrors = '0, 16';
        $wmMashineWorkErrors = '9, 10, 11, 12, 13, 14, 21, 25';
        $queryCpOk = "AND packet NOT IN (".$cpErrors.") AND evt_bill_validator NOT IN (".$evtBillErrors.")";
        $queryMashineOk = "AND current_status NOT IN (".$wmMashineConnectionErrors.",".$wmMashineWorkErrors.")";

        $dbHelper->getBaseUnitQueryByTimestamps($timestamp, $endTimestamp, new ImeiData(), $imei, 'imei_id', 'created_at, imei_id');

        $dbHelper->addQueryString($queryCpOk);

        if ($dbHelper->getCount() == 0) {
    
            return ['error' => self::TYPE_CP_ERROR];
        }

        $dbHelper->getBaseUnitQueryByTimestamps($timestamp, $endTimestamp, new WmMashineData(), $this, 'mashine_id', 'created_at, mashine_id');
        $dbHelper->addQueryString($queryMashineOk);

        if ($dbHelper->getCount() == 0) {

            return ['error' => self::MASHINE_ERROR];
        }

        return ['error' => self::TYPE_IDLES_OK];
    }
    
    /**
     * Gets idle key by results of 'getIfIdlesOk' method
     * 
     * @param int $timestamp
     * @param int $endTimestamp
     * @param Imei $imei
     * @param array $idlesResult
     * 
     * @return string
     */
    public function getIdleKey($timestamp, $endTimestamp, $imei, $idlesResult)
    {
        if ($idlesResult['error'] == self::TYPE_CP_ERROR) {
            if ($this->getIfCpIdles($timestamp, $endTimestamp, $imei)) {

                return 'cbIdleHours';
            }

            return 'baIdleHours';
        }

        if ($this->getIfMashineConnectionIdles($timestamp, $endTimestamp, $imei)) {

            return 'connectIdleHours';
        }

        return 'workIdleHours';
    }

    /**
     * Basic idle hours method
     * 
     * @param int $start
     * @param int $end
     * @param int $endTimestamp
     * @param double $timeIdleHours
     *
     * @return array
     */
    public function getWmMashineIdleHoursBase(
        $start, $end, $endTimestamp, $timeIdleHours
    ) {
        $dbHelper = Yii::$app->dbCommandHelperOptimizer;
        $item = null;
        $stepInterval = $timeIdleHours*3600;
        $fieldInst = 'mashine_id';
        $select = 'created_at, mashine_id';

        $baIdleHours = 0.00;
        $cbIdleHours = 0.00;
        $connectIdleHours = 0.00;
        $workIdleHours = 0.00;

        $imei = Imei::find()->where(['id' => $this->imei_id])->limit(1)->one();

        for ($timestamp = $start; $endTimestamp <= $end; $timestamp += $stepInterval, $endTimestamp = $timestamp + $stepInterval) {

            $idlesResult = $this->getIfIdlesOk($timestamp, $endTimestamp, $imei);

            if ($idlesResult['error'] == self::TYPE_IDLES_OK) {
                $dbHelper->addQueryString("ORDER BY created_at DESC LIMIT 1 ");
                $item = $dbHelper->getItem();
                $item = (object)$item;

                $timestamp = $item->created_at - $stepInterval + 1;
                continue;
            } else {
                $idlesKey = $this->getIdleKey($timestamp, $endTimestamp, $imei, $idlesResult);
                $dbHelper->getBaseUnitQueryByTimestamps($endTimestamp, $end, new WmMashineData(), $this, $fieldInst, $select);

                if ($dbHelper->getCount() == 0) {
                    $$idlesKey += ((float)$end - $timestamp) / 3600;
                    break;
                } else {
                    $dbHelper->addQueryString("ORDER BY created_at ASC LIMIT 1 ");
                    $item = $dbHelper->getItem();
                    $item = (object)$item;
                    $timeDiff = ($item->created_at < $end ? $item->created_at : $end) - $endTimestamp;
                    $$idlesKey += $timeIdleHours + ((float)$timeDiff / 3600);
                    $timestamp = $item->created_at - $stepInterval + 1;
                    continue;
                }
            }
        }

        $idleHours = $workIdleHours + $connectIdleHours + $baIdleHours + $cbIdleHours;

        return [
            'workIdleHours' => $workIdleHours,
            'connectIdleHours' => $connectIdleHours,
            'baIdleHours' => $baIdleHours,
            'cbIdleHours' => $cbIdleHours,
            'idleHours' => $idleHours,
            'record' => $item
        ];
    }

    /**
     * Gets idle hours data from `j_temp`
     * 
     * @param int $start
     * @param int $end
     * @param array $baseIdlesKeys
     * @param double $allHours
     * @param string $paramType
     * @param double $stepInterval
     * @param int $todayBeginning
     * @param EntityHelper $entityHelper
     * 
     * @return array|bool
     */
    public function getIdleHoursDataFromTemp(
        $start, $end, $baseIdlesKeys, $allHours, $paramType, $stepInterval, $todayBeginning, $entityHelper
    )
    {
        $dbHelper = Yii::$app->dbCommandHelperOptimizer;

        if ($start >= $todayBeginning &&
            ($tempIdleData = $entityHelper->getUnitTempValue($start, $end, $this, $paramType, $stepInterval))
        ) {
            $baseIdlesData = json_decode($tempIdleData['value'], true);
            $start = $tempIdleData['end'];
            $endTimestamp = $start + $stepInterval;

            if ($start + $stepInterval > $end) {
                $delta = ($baseIdlesData['idleHours']/$allHours)*(($end - $start) / 3600);

                if ($delta + $baseIdlesData['idleHours'] > $allHours) {
                    $delta = $allHours - $baseIdlesData['idleHours'];
                }

                foreach ($baseIdlesKeys as $key) {
                    $baseIdlesData[$key] += ($baseIdlesData[$key]/$baseIdlesData['idleHours']) * $delta;
                }

                $baseIdlesData['allHours'] = $allHours;

                return ['data' => $baseIdlesData, 'id' => $tempIdleData['id'], 'start' => $start, 'endTimestamp' => $endTimestamp, 'isFinal' => true];
            }

            return ['data' => $baseIdlesData, 'id' => $tempIdleData['id'], 'start' => $start, 'endTimestamp' => $endTimestamp, 'isFinal' => false];
        }

        return false;
    }

    /**
     * Puts idle hours data to `j_temp`
     * 
     * @param array $idlesData
     * @param array $baseIdlesKeys
     * @param int $id
     * @param int $start
     * @param int $end
     * @param int $baseStart
     * @param string $paramType
     * @param int $todayBeginning
     * @param BalanceHolderSummarySearch $bhSummarySearch
     * 
     * @return array
     */
    public function putIdleHoursDataToTemp(
        $idlesData, $baseIdlesKeys, $id, $start, $end, $baseStart, $paramType, $todayBeginning, $bhSummarySearch
    )
    {
        $dbHelper = Yii::$app->dbCommandHelperOptimizer;

        if ($start >= $todayBeginning) {
            $other = !is_null($idlesData['record']) ? $idlesData['record']->created_at : null;
            unset($idlesData['record']);

            foreach ($baseIdlesKeys as $key) {
                $idlesData[$key] = $bhSummarySearch->parseFloat($idlesData[$key], 2);
            }

            $item = [
                'value' => json_encode($idlesData), 
                'start' => $baseStart,
                'end' => $end,
                'entity_id' => $this->id,
                'type' => str_replace(["\\"], ["/"], self::className()),
                'param_type' => $paramType,
                'other' => $other
            ];

            if ($id) {
                $item['id'] = $id;
            }

            $dbHelper->upsertUnitTempItem($item);
        } else {
            unset($idlesData['record']);
        }

        return $idlesData;
    }

    /**
     * Main method to get idle hours by types
     * 
     * @param int $start
     * @param int $end
     * @param double $timeIdleHours
     * 
     * @return array
     */
    public function getIdleHoursByTimestamps($start, $end, $timeIdleHours)
    {
        $paramType = "idleHoursReasons";
        $idleHours = 0.00;
        $stepInterval = $timeIdleHours * 3600;
        $entityHelper = new EntityHelper();
        $timestampsData = $entityHelper->makeUnitTimestamps($start, $end, $this, $timeIdleHours);
        extract($timestampsData);

        $bhSummarySearch = new BalanceHolderSummarySearch();
        $allHours = $bhSummarySearch->parseFloat(($end - $start) / 3600, 2);

        $baseIdlesData =  [
            'workIdleHours' => 0,
            'connectIdleHours' => 0,
            'baIdleHours' => 0,
            'cbIdleHours' => 0,
            'idleHours' => 0,
            'allHours' => $allHours
        ];

        $baseIdlesKeys = [
            'workIdleHours',
            'connectIdleHours',
            'baIdleHours',
            'cbIdleHours',
            'idleHours'
        ];

        if ($start + $stepInterval > $end) {

            return $baseIdlesData;
        }

        $baseStart = $start;
        $id = null;

        $tempData = $this->getIdleHoursDataFromTemp(
            $start, $end, $baseIdlesKeys, $allHours, $paramType, $stepInterval, $todayBeginning, $entityHelper
        );

        if ($tempData && $tempData['isFinal']) {

            return $tempData['data'];
        } elseif ($tempData) {
            $baseIdlesData = $tempData['data'];
            $id = $tempData['id'];
            $start = $tempData['start'];
            $endTimestamp = $tempData['endTimestamp'];
        }

        $idlesData = $this->getWmMashineIdleHoursBase($start, $end, $endTimestamp, $timeIdleHours);

        foreach ($baseIdlesKeys as $key) {
            $idlesData[$key] += $baseIdlesData[$key];
        }

        $idlesData['allHours'] = $allHours;

        return $this->putIdleHoursDataToTemp(
            $idlesData, $baseIdlesKeys, $id, $start, $end,
            $baseStart, $paramType, $todayBeginning, $bhSummarySearch
        );
    }
}
