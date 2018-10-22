<?php

namespace frontend\models;

use common\models\User;
use DateTime;
use frontend\services\custom\Debugger;
use frontend\services\globals\Entity;
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

    private $wm;

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
        return parent::find()
//            ->where(['status' => WmMashine::STATUS_ACTIVE])
            ->where(['wm_mashine.is_deleted' => false]);
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
        return WmMashine::find()->where(['status' => WmMashine::STATUS_OFF])->all();
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

//            ->select('model, COUNT(*) AS count')
//            ->groupBy('model')
//            ->andWhere(['company_id' => $user->company_id])
//            ->all();

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
        $actualityClass = 'ping-not-actual';
        $halfHourBeforeTimestamp = strtotime("-30 minutes") + Jlog::TYPE_TIME_OFFSET;

        if ($this->ping >= $halfHourBeforeTimestamp) {
            $actualityClass = 'ping-actual';
        }

        $formattedDate = Yii::$app->formatter->asDate($this->ping, self::PHP_DATE_TIME_FORMAT);

        return "<span class='$actualityClass'>".$formattedDate."</span>";
    }
}
