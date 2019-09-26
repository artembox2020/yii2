<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii2tech\ar\softdelete\SoftDeleteBehavior;
use frontend\services\globals\Entity;
use frontend\services\globals\DateTimeHelper;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "address_balance_holder".
 *
 * @property int $id
 * @property int $company_id
 * @property int $balance_holder_id
 * @property string $name
 * @property string $address
 * @property int $floor
 * @property int $created_at
 * @property int $updated_at
 * @property int $is_deleted
 * @property int $deleted_at
 * @property int $number_of_floors
 * @property int $date_inserted
 * @property int $date_connection_monitoring
 * @property int $number_of_citizens
 *
 * @property BalanceHolder $balanceHolder
 * @property Imei[] $imeis
 */
class AddressBalanceHolder extends \yii\db\ActiveRecord
{
    const STATUS_FREE = 0;
    const STATUS_BUSY = 1;

    const INFINITY = 99999999;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address_balance_holder';
    }

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
    public function rules()
    {
        return [
            ['name', 'trim'],
            ['name', 'unique', 'targetClass' => AddressBalanceHolder::className()],
            ['date_inserted', 'filter', 'filter' => 'strtotime', 'skipOnEmpty' => true],
            ['date_connection_monitoring', 'filter', 'filter' => 'strtotime', 'skipOnEmpty' => true],
            [['company_id', 'balance_holder_id', 'number_of_floors', 'created_at', 'updated_at', 'deleted_at', 'number_of_citizens'], 'integer'],
            [['balance_holder_id', 'address', 'number_of_citizens'], 'required'],
            [['number_of_citizens'], 'number', 'min' => 1],
            [['name', 'address', 'floor'], 'string', 'max' => 255],
            [['balance_holder_id'], 'exist', 'skipOnError' => true, 'targetClass' => BalanceHolder::className(), 'targetAttribute' => ['balance_holder_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('frontend', 'ID'),
            'company_id' => Yii::t('frontend', 'Company'),
            'name' => Yii::t('frontend', 'Address Name'),
            'address' => Yii::t('frontend', 'Address'),
            'floor' => Yii::t('frontend', 'Floor'),
            'number_of_floors' => Yii::t('frontend', 'Number of Floors'),
            'balance_holder_id' => Yii::t('frontend', 'Balance Holder'),
            'date_inserted' => Yii::t('frontend', 'Date Inserted'),
            'date_connection_monitoring' => Yii::t('frontend', 'Date connection monitoring'),
            'countWashMachine' => Yii::t('frontend', 'Count Wash Machine'),
            'countGelDispenser' => Yii::t('frontend', 'Count Gd Machine'),
            'created_at' => Yii::t('frontend', 'Created At'),
            'updated_at' => Yii::t('frontend', 'Updated At'),
            'is_deleted' => Yii::t('frontend', 'Is Deleted'),
            'deleted_at' => Yii::t('frontend', 'Deleted At'),
            'imeis' => Yii::t('frontend', 'Imei'),
            'number_of_citizens' => Yii::t('frontend', 'Number Of Citizens')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBalanceHolder()
    {
        return $this->hasOne(BalanceHolder::className(), ['id' => 'balance_holder_id']);
    }

    /**
     * @return BalanceHolder
     */
    public function getFakeBalanceHolder()
    {
        return BalanceHolder::find()->where(['id' => $this->balance_holder_id])->one();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImeis()
    {
        return $this->hasMany(Imei::className(), ['address_id' => 'id'])
                    ->andWhere(['status' => Imei::STATUS_ACTIVE]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImei()
    {
        return $this->hasOne(Imei::className(), ['address_id' => 'id'])
                    ->andWhere(['status' => Imei::STATUS_ACTIVE]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFakeImei()
    {
        return $this->hasOne(Imei::className(), ['address_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWashMachines()
    {
        return $this->hasMany(WmMashine::className(), ['address_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGelDispenser()
    {
        return $this->hasMany(GdMashine::className(), ['address_id' => 'id']);
    }
    
    /**
     * WashMashines to address Count
     * @return int|string
     */
    public function getCountWashMachine() {
        
        return $this->getWashMachines()->count();
    }

    /**
     * GdMashines to address Count
     * @return int|string
     */
    public function getCountGelDispenser() {
        
        return $this->getGelDispenser()->count();
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public static function find()
    {
        return parent::find()->where(['address_balance_holder.is_deleted' => false]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public static function findAllByCompany()
    {
        $entity = new Entity();

        return parent::find()->where(['company_id' => $entity->getCompanyId()]);
    }

    /**
     * @return true|false
     */
    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {

            return false;
        }

        // release status of the model to be deleted
        $this->status = AddressBalanceHolder::STATUS_FREE;
        $this->save(false);

        $entity = new Entity();
        $imeis = $entity->getUnitsQueryPertainCompany(new Imei())
                        ->andWhere(['address_id' => $this->id, 'status' => Imei::STATUS_ACTIVE])
                        ->all();
        foreach($imeis as $imei) {
            $imei->status = Imei::STATUS_OFF;
            $imei->save();
        }

        return true;
    }

    /**
     * @param bool $insert
     * @param array $attr
     */
    public function afterSave($insert, $attr)
    {
        parent::afterSave($insert, $attr);

        if (!$insert && $this->status == AddressBalanceHolder::STATUS_BUSY) {
            if (!empty($attr['balance_holder_id']) && $this->balance_holder_id != $attr['balance_holder_id']) {
                $imei = $this->imei;
                $imei->balance_holder_id = $this->balance_holder_id;
                $imei->save(false);

                foreach ($this->washMachines as $washMachine) {
                    $washMachine->balance_holder_id = $this->balance_holder_id;
                    $washMachine->save(false);
                }

                foreach ($this->gelDispenser as $gelDispenser) {
                    $gelDispenser->balance_holder_id = $this->balance_holder_id;
                    $gelDispenser->save(false);
                }
            }
        }
    }

    /**
     * Displays serial number (e.g. for views)
     * 
     * @return int|string
     */
    public function displaySerialNumber()
    {
        if ($this->serial_column == AddressBalanceHolder::INFINITY) {

            return '';
        }

        return $this->serial_column;
    }

    /**
     * Initializes serial number
     *
     */
    public function initSerialNumber()
    {
        if (empty($this->serial_column)) {
            $this->serial_column = self::INFINITY;
            $this->save(false);
        }
    }

    /**
     * Renders terminal info block view
     *
     */
    public function getTerminalInfoView()
    {
        $addressImeiData = new AddressImeiData();
        $imei = $addressImeiData->getCurrentImeiIdByAddress($this->id, $this->status);

        if (empty($imei)) {

            return false;
        }

        $imeiDataSearch = new ImeiDataSearch();

        return Yii::$app->runAction(
            '/monitoring/render-terminal-data-by-imei-id',
            ['imeiId' => $imei->id, 'searchModel' => $imeiDataSearch]
        );
    }

    /**
     * Gets income by the current day
     *
     */
    public function getCurrentDayIncome()
    {
        $addressImeiData = new AddressImeiData();
        $imei = $addressImeiData->getCurrentImeiIdByAddress($this->id, $this->status);
        
        if (!$imei) {

            return 0;
        }

        $bhSummarySearch = new BalanceHolderSummarySearch();
        $dateTimeHelper = new DateTimeHelper();
        $start = $dateTimeHelper->getTodayBeginningTimestamp();
        $end = $dateTimeHelper->getRealUnixTimeOffset(0);

        return $bhSummarySearch->getIncomeByImeiAndTimestamps($start, $end, $imei, $this);
    }

    /**
     * Gets whether address was deleted between start and end timestamps
     * @param int $start
     * @param int $end
     *
     * @return bool 
     */
    public function isDeletedByTimestamps($start, $end)
    {
        if ($this->is_deleted && $this->deleted_at <= $end && $this->deleted_at >= $start) {

            return true;
        }

        return false;
    }

    /**
     * Gets whether address was created between start and end timestamps
     * @param int $start
     * @param int $end
     *
     * @return bool 
     */
    public function isCreatedByTimestamps($start, $end)
    {
        if ($this->created_at <= $end && $this->created_at >= $start) {

            return true;
        }

        return false;
    }

    /**
     * Gets address ids by company id
     * @param int $companyId
     *
     * @return array
     */
    public function getAddressIdsByCompanyId(int $companyId)
    {
        $items = AddressBalanceHolder::find()->select(['id'])->where(['company_id' => $companyId])->all();

        return ArrayHelper::getColumn($items, 'id');
    }

    /**
     * Gets imei data by current address
     *
     * @return string
     */
    public function getImeiData()
    {
        ob_start();
        if ($this->imei): ?>
            IMEI:
            <?= Yii::$app->commonHelper->link($this->imei) ?>
            Init: 
            <?= $this->imei->getInit() ?>
            <?= \Yii::$app->formatter->asDate($this->imei->updated_at, 'short') ?>
        <?php
        endif;

        return ob_get_clean();
    }

    /**
     * Gets mashines data by current address
     *
     * @param array $mashines
     * 
     * @return string
     */
    public function getMashinesData($mashines)
    {
        ob_start();

        foreach ($mashines as $mashine) {

            echo Yii::$app->commonHelper->link(
                $mashine,
                [],
                Yii::t('frontend', 'WM').' '.$mashine->number_device.
                '(status:'.Yii::t('frontend', $mashine->getState()).')'
            ).'<br>';
        }

        return ob_get_clean();
    }
}
