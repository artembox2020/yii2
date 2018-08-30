<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii2tech\ar\softdelete\SoftDeleteBehavior;

/**
 * This is the model class for table "imei_data".
 *
 * @property int $id
 * @property int $imei_id
 * @property int $created_at
 * @property integer $date
 * @property string $imei
 * @property double $level_signal
 * @property float $on_modem_account
 * @property double $in_banknotes
 * @property float $money_in_banknotes
 * @property float $fireproof_residue
 * @property double $price_regim
 * @property int $updated_at
 * @property int $is_deleted
 * @property int $deleted_at
 * @property int $status
 *
// * @property Imei $imei
 * @property WmMashine[] $wmMashines
 */
class ImeiData extends \yii\db\ActiveRecord
{
    public $bill_acceptance_fullness;
    public $bill_acceptance;
    public $software_versions;
    public $actions;
    public $date_last_encashment;
    public $counter_last_encashment;
    public $counter_zeroing;
    public $technical_injection;

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
                    'deleted_at' => time()
                ],
            ],
            TimestampBehavior::className()
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'imei_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['imei_id'], 'required'],
            [['imei_id', 'created_at', 'updated_at', 'deleted_at'], 'integer'],
            [['imei_id'], 'exist', 'skipOnError' => true, 'targetClass' => Imei::className(), 'targetAttribute' => ['imei_id' => 'id']],
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
//            'company_id' => Yii::t('frontnd', 'Company'),
            'status' => Yii::t('frontend', 'Status'),
            'created_at' => Yii::t('frontend', 'Created At'),
            'imei' => Yii::t('frontend', 'Imei'),
            'level_signal' => Yii::t('frontend', 'Level Signal'),
            'on_modem_account' => Yii::t('frontend', 'On Modem Account'),
            'in_banknotes' => Yii::t('frontend', 'In Banknotes'),
            'money_in_banknotes' => Yii::t('frontend', 'Money In Banknotes'),
            'fireproof_residue' => Yii::t('frontend', 'Fireproof Residue'),
            'price_regim' => Yii::t('frontend', 'Price Regim'),
            'updated_at' => Yii::t('frontend', 'Updated At'),
            'is_deleted' => Yii::t('frontend', 'Is Deleted'),
            'deleted_at' => Yii::t('frontend', 'Deleted At'),
            'bill_acceptance_fullness' => Yii::t('frontend', 'Bill Acceptance Fullness'),
            'bill_acceptance' => Yii::t('frontend', 'Bill Acceptance'),
            'software_versions' => Yii::t('frontend', 'Software Versions'),
            'actions' => Yii::t('frontend', 'Actions'),
            'date_last_encashment' => Yii::t('frontend', 'Date Last Encashment'),
            'counter_last_encashment' => Yii::t('frontend', 'Counter Last Encashment'),
            'counter_zeroing' => Yii::t('frontend', 'Counter Zeroing'),
            'technical_injection' => Yii::t('frontend', 'Technical Injection')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImeiRelation()
    {
        return $this->hasOne(Imei::className(), ['id' => 'imei_id']);
    }

    public static function find()
    {
        return parent::find()->where(['imei_data.is_deleted' => false]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWmMashines()
    {
        return $this->hasMany(WmMashine::className(), ['imei_id' => 'id']);
    }

    /**
     * Gets bill acceptance data view
     */
    public function getBillAcceptanceData()
    {
        $result ='';

        if (!empty($this->imeiRelation->capacity_bill_acceptance) && !empty($this->in_banknotes)) {
            $fullness = (int)$this->in_banknotes / (int)$this->imeiRelation->capacity_bill_acceptance;
            $fullness = $fullness * 100;
            $fullness = number_format($fullness, 2);
        } else {
            $fullness = null;
        }
        
        return Yii::$app->view->render(
            '/monitoring/data/billAcceptanceData',
            ['model' => $this, 'fullness' => $fullness]
        );
    }

    /**
     * Gets software versions view
     */
    public function getSoftwareVersions()
    {

        return Yii::$app->view->render(
            '/monitoring/data/softwareVersions',
            ['model' => $this]
        );
    }

    /**
     * Gets current modem card info view
     */
    public function getModemCard()
    {

        return Yii::$app->view->render('/monitoring/data/modemCard', ['model' => $this]);
    }
}
