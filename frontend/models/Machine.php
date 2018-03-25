<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "machine".
 *
 * @property int $id
 * @property int $imei_id
 * @property int $num_dev
 * @property int $created_at
 * @property int $is_deleted
 * @property int $deleted_at
 *
 * @property Imei $imei
 */
class Machine extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'machine';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['imei_id'], 'required'],
            [['imei_id', 'num_dev', 'created_at', 'deleted_at'], 'integer'],
            [['is_deleted'], 'string', 'max' => 1],
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
            'num_dev' => Yii::t('frontend', 'Num Dev'),
            'created_at' => Yii::t('frontend', 'Created At'),
            'is_deleted' => Yii::t('frontend', 'Is Deleted'),
            'deleted_at' => Yii::t('frontend', 'Deleted At'),
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
