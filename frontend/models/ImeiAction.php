<?php

namespace frontend\models;

use DateTime;
use frontend\services\custom\Debugger;
use nepster\basis\helpers\DateTimeHelper;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii2tech\ar\softdelete\SoftDeleteBehavior;

/**
 * Class ImeiAction
 * @property int $id
 * @property int $imei_id
 * @property integer $imei
 * @property integer $unix_time_offset
 * @property string $action
 * @property boolean $is_active
 * @property boolean $is_cancelled
 * @property boolean $is_deleted
 */
class ImeiAction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {

        return 'imei_action';
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
                'class' => TimestampBehavior::className()
            ]
        ];
    }

    public function rules() {

        return [
            /* your other rules */
            [['created_at', 'updated_at', 'deleted_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('frontend', 'ID'),
            'imei' => Yii::t('frontend', 'Imei'),
        ];
    }

    /**
     * @return $this|\yii\db\ActiveQuery
     */
    public static function find()
    {
        return parent::find()->where(['imei_action.is_deleted' => false, 'imei_action.is_active' => true]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImei()
    {
        return $this->hasOne(Imei::className(), ['id' => 'imei_id']);
    }

    /**
     * Fetches the last non-cancelled item by imei id and timestamp
     *
     * @param integer $imeiId
     * @param integer $timestamp
     * @return ImeiAction|null
     */
    private function fetchItem($imeiId, $timestamp = false)
    {
        // fetch item from the table
        $item = ImeiAction::find()->where(['imei_id' => $imeiId, 'is_cancelled' => false, 'is_deleted' => false]);

        if ($timestamp) {
            $item = $item->andWhere(['<=', 'unix_time_offset', $timestamp]);
        }

        $item = $item->orderBy(['unix_time_offset' => SORT_DESC, 'id' => SORT_DESC])
                     ->limit(1)
                     ->one();

        return $item;
    }

    /**
     * Fetches the last item by imei id in spite of cancelled or not 
     *
     * @param integer $imeiId
     * @return ImeiAction|null
     */
    private function fetchLastItem($imeiId)
    {
        // fetch item from the table
        $item = ImeiAction::find()->where(['imei_id' => $imeiId, 'is_deleted' => false]);

        $item = $item->orderBy(['unix_time_offset' => SORT_DESC, 'id' => SORT_DESC])
                     ->limit(1)
                     ->one();

        return $item;
    }

    /**
     * Cancellates item
     *
     * @param ImeiAction $item
     * @return ImeiAction|null
     */
    private function makeItemCancellation($item)
    {
        if ($item && !$item->is_cancelled && $item->is_active) {
            $item->is_cancelled = true;
            $item->is_active = false;
            $item->save();
        }

        return $item;
    }

    /**
     * Appends new item or cancels the last one by imei id
     *
     * @param integer $imeiId
     * @param Imei $imei
     * @param string $action
     * @param bool $isCancel
     * @return ImeiAction|null
     */
    public function appendAction($imeiId, $imei, $action, $isCancel)
    {
        $imeiAction = $this->fetchLastItem($imeiId);
        $itemCancellationResult = $this->makeItemCancellation($imeiAction);

        if ($isCancel) {

            return $itemCancellationResult;
        }

        $imeiAction = new ImeiAction();
        $imeiAction->is_active = true;

        // fill in the table with actual data
        $imeiAction->imei_id = $imeiId;
        $imeiAction->imei = $imei;
        $imeiAction->action = $action;
        $imeiAction->is_cancelled = false;
        $imeiAction->is_deleted = false;
        $imeiAction->unix_time_offset = time() + Jlog::TYPE_TIME_OFFSET;
        $imeiAction->save();

        return $imeiAction;
    }

    /**
     * Gets actual action by imei and timestamp
     *
     * @param integer $imeiId
     * @param integer $timestamp
     * @return string|bool
     */
    public function getAction($imeiId, $timestamp)
    {
        $imeiAction = $this->fetchItem($imeiId, $timestamp);

        // return false in case of empty imeiAction
        if (!$imeiAction) {

            return false;
        }

        $lastImeiAction = $this->fetchItem($imeiId);

        // last action item must be active, otherwise return false
        if ($lastImeiAction->unix_time_offset == $imeiAction->unix_time_offset && !$imeiAction->is_active) {

            return false;
        }

        $imeiAction->is_active = false;
        $imeiAction->save();

        if ($imeiAction->action == ImeiData::TYPE_ACTION_TIME_SET) {

            return $imeiAction->action.'&'.(time() + Jlog::TYPE_TIME_OFFSET);
        }

        return $imeiAction->action;
    }

    /**
     * Makes class indicating whether active action
     *
     * @param integer $imeiId
     * @param string $action
     * @return string
     */
    public function makeClass($imeiId, $action)
    {
        // fetch item from the table
        $imeiAction = $this->fetchItem($imeiId);

        if (!$imeiAction || !$imeiAction->is_active || $imeiAction->action != $action) {

            return '';
        } else {

            return 'active';
        }
    }
}
