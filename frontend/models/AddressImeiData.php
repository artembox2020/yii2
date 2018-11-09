<?php

namespace frontend\models;
use yii\db\ActiveRecord;
use frontend\services\globals\Entity;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii2tech\ar\softdelete\SoftDeleteBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "address_imei_data".
 *
 * @property integer $address_id
 * @property integer $imei_id
 * @property int $created_at
 * @property int $updated_at
 * @property int $is_deleted
 * @property int $deleted_at
 */
class AddressImeiData extends ActiveRecord
{

    const INFINITY = 99999999999999;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address_imei_data';
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
            [['address_id', 'imei_id', 'created_at', 'updated_at', 'deleted_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'address_id' => Yii::t('frontend', 'Address ID'),
            'imei_id' => Yii::t('frontend', 'Imei ID'),
            'created_at' => Yii::t('frontend', 'Created At'),
            'updated_at' => Yii::t('frontend', 'Updated At'),
            'is_deleted' => Yii::t('frontend', 'Is Deleted'),
            'deleted_at' => Yii::t('frontend', 'Deleted At'),
        ];
    }

    /**
     * Writes item to the table and  returns its id
     * 
     * @param int $imeiId
     * @param int $addressId
     * @return int
     */
    public function createLog($imeiId, $addressId)
    {
        $query = AddressImeiData::find();
        $addressImeiItem = $query->andWhere(['imei_id' => $imeiId])
                                 ->orderBy(['created_at' => SORT_DESC])
                                 ->limit(1)
                                 ->one();

        if (!$addressImeiItem || $addressImeiItem->address_id != $addressId) {
            $addressImei = new AddressImeiData();
            $addressImei->address_id = $addressId;
            $addressImei->imei_id = $imeiId;
            $addressImei->is_deleted = false;
            $addressImei->save();

            return $addressImei->id;
        }

        return 0;
    }

    /**
     * Gets next imei id by address and timestamp (find last binding of the day)
     * 
     * @param int $addressId
     * @param int $timestamp
     * @return array
     */
    public function getNextImeiIdByAddressAndTimestamp($addressId, $timestamp)
    {
        $query = AddressImeiData::find();
        $item = $query->andWhere(['address_id' => $addressId])
                      ->andWhere(['>', 'created_at', $timestamp])
                      ->orderBy(['created_at' => SORT_ASC])
                      ->limit(1)
                      ->one();

        if (!$item) {

            return [];
        }

        $nextDayTimestamp = $this->getNextDayBeginningByTimestamp($item->created_at);

        $query = AddressImeiData::find();
        $item = $query->andWhere(['address_id' => $addressId])
                      ->andWhere(['<', 'created_at', $nextDayTimestamp])
                      ->orderBy(['created_at' => SORT_DESC])
                      ->limit(1)
                      ->one();

        if ($item->imei_id == 0) {

            return [
                'imei_id' => $item->imei_id,
                'created_at' => $item->created_at,
                'id' => $item->id
            ];
        }

        $query = AddressImeiData::find();
        $itemImei = $query->andWhere(['imei_id' => $item->imei_id])
                      ->andWhere(['<', 'created_at', $nextDayTimestamp])
                      ->orderBy(['created_at' => SORT_DESC])
                      ->limit(1)
                      ->one();

        if ($itemImei->id != $item->id) {

            return $this->getNextImeiIdByAddressAndTimestamp($addressId, $item->created_at);
        }

        return [
            'imei_id' => $item->imei_id,
            'created_at' => $item->created_at,
            'id' => $item->id
        ];
    }

    /**
     * Gets current imei by address 
     * 
     * @param int $addressId
     * @param int $addressStatus
     * @return int
     */
    public function getCurrentImeiIdByAddress($addressId, $addressStatus)
    {

        return Imei::find()->andWhere(['address_id' => $addressId, 'status' => $addressStatus])->limit(1)->one();
    }

    /**
     * Gets history beginning by address id
     * 
     * @param int $addressId
     * @return int
     */
    public function getHistoryBeginning($address_id)
    {
        $item = AddressImeiData::find()->andWhere(['address_id' => $address_id])
                                        ->orderBy(['created_at' => SORT_ASC])
                                        ->limit(1)
                                        ->one();

        return $item ? $item->created_at : self::INFINITY;
    }

    /**
     * Gets next day beginning timestamp by timestamp
     * 
     * @param timestamp $timestamp
     * @return int
     */
    public function getNextDayBeginningByTimestamp($timestamp)
    {
        $Y = date('Y', $timestamp);
        $m = date('m', $timestamp);
        $d = date('d', $timestamp);

        return strtotime($Y.'-'.$m.'-'.$d.' 00:00:00') + 3600 *24;
    }

    /**
     * Gets all mashine queries info as array by address and timestamp interval
     * 
     * @param int $addressId
     * @param int $addressStatus
     * @param timestamp $timestampStart
     * @param timestamp $timestampEnd
     * @return array
     */
    public function getWmMashinesQueries($addressId, $addressStatus, $timestampStart, $timestampEnd)
    {
        $timestamp = $timestampStart;
        $wmMashinesQueries = [];
        $bhSummarySearch = new BalanceHolderSummarySearch();
        $historyBeginning = $this->getHistoryBeginning($addressId);
        $currentImei = $this->getCurrentImeiIdByAddress($addressId, $addressStatus);

        // in case of before history beginning make query info from current imei 
        if ($timestamp < $historyBeginning) {
            if (!empty($currentImei)) {
                $query = $bhSummarySearch->getAllMashinesQueryByTimestamps($timestamp, $historyBeginning, $currentImei->id);
            }

            $wmMashinesQueries[] = [
                'created_at' => $timestamp,
                'query' => !empty($currentImei) ? $query : false,
                'imei_id' => !empty($currentImei) ? $currentImei->id : 0
            ];

            $timestamp = $historyBeginning;
        }

        --$timestamp;
        $imeiInfo = $this->getNextImeiIdByAddressAndTimestamp($addressId, $timestamp);

        while ($timestamp <= $timestampEnd) {

            if (empty($imeiInfo)) {
                break;
            }

            $nextImeiInfo = $this->getNextImeiIdByAddressAndTimestamp($addressId, $imeiInfo['created_at']);

            if (!empty($nextImeiInfo)) {
                $timestamp = $nextImeiInfo['created_at'];
            } else {
                $timestamp = self::INFINITY;
            }

            if (!empty($imeiInfo['imei_id'])) {
                $query = $bhSummarySearch->getAllMashinesQueryByTimestamps($imeiInfo['created_at'], $timestamp, $imeiInfo['imei_id']);
            }

            $wmMashinesQueries[] = [
                'created_at' => $imeiInfo['created_at'],
                'query' => !empty($imeiInfo['imei_id']) ? $query : false,
                'imei_id' => !empty($imeiInfo['imei_id']) ? $imeiInfo['imei_id'] : 0
            ];

            $imeiInfo = $nextImeiInfo;
        }

        return $wmMashinesQueries;
    }

    /**
     * Gets WM mashines count by queries info
     * 
     * @param array $queriesInfo
     * @return int
     */
    public function getWmMashinesCountByMashineQueries($queriesInfo)
    {
        $mashineIds = [];
        foreach ($queriesInfo as $queryInfo) {

            if (empty($queryInfo['query'])) {
                continue;
            }

            $mashines = $queryInfo['query']->all();
            $mashineIds = array_merge($mashineIds, array_diff(ArrayHelper::getColumn($mashines, 'id'), $mashineIds));
        }

        return count($mashineIds);
    }

    /**
     * Gets WM mashines count by year and month
     * 
     * @param int $year
     * @param int $month
     * @param AddressBalanceHolder $address
     * @return int
     */
    public function getWmMashinesCountByYearMonth($year, $month, $address)
    {
         $bhSummarySearch = new BalanceHolderSummarySearch();
         $timestamps = $bhSummarySearch->getTimestampByYearMonth($year, $month);
         $queries = $this->getWmMashinesQueries($address->id, $address->status, $timestamps['start'], $timestamps['end']);

         return $this->getWmMashinesCountByMashineQueries($queries);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public static function find()
    {
        return parent::find()->where(['address_imei_data.is_deleted' => false]);
    }
}
