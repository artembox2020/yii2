<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "gd_mashine".
 *
 * @property int $id
 * @property int $imei_id
 * @property int $serial_number
 * @property int $gel_in_tank
 * @property int $status
 *
 * @property Imei $imei
 */
class GdMashine extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gd_mashine';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['imei_id'], 'required'],
            [['imei_id', 'serial_number', 'gel_in_tank', 'status'], 'integer'],
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
            'serial_number' => Yii::t('frontend', 'Serial Number'),
            'gel_in_tank' => Yii::t('frontend', 'Gel In Tank'),
            'status' => Yii::t('frontend', 'Status'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImei()
    {
        return $this->hasOne(Imei::className(), ['id' => 'imei_id']);
    }
}
