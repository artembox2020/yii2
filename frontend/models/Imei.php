<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "imei".
 *
 * @property int $id
 * @property int $imei
 * @property int $floor_id
 * @property int $created_at
 * @property int $is_deleted
 * @property int $deleted_at
 *
 * @property Floor $floor
 * @property Machine[] $machines
 */
class Imei extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'imei';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['imei', 'floor_id', 'created_at', 'deleted_at'], 'integer'],
            [['floor_id'], 'required'],
            [['is_deleted'], 'string', 'max' => 1],
            [['floor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Floor::className(), 'targetAttribute' => ['floor_id' => 'id']],
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
            'floor_id' => Yii::t('frontend', 'Floor ID'),
            'created_at' => Yii::t('frontend', 'Created At'),
            'is_deleted' => Yii::t('frontend', 'Is Deleted'),
            'deleted_at' => Yii::t('frontend', 'Deleted At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFloor()
    {
        return $this->hasOne(Floor::className(), ['id' => 'floor_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMachines()
    {
        return $this->hasMany(Machine::className(), ['imei_id' => 'id']);
    }
}
