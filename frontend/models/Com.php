<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "com".
 *
 * @property integer $id
 * @property string $imei
 * @property string $comand
 * @property string $status
 * @property integer $date_sent
 */
class Com extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'com';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date_sent'], 'integer'],
            [['imei'], 'string', 'max' => 250],
            [['comand'], 'string', 'max' => 10],
            [['status'], 'string', 'max' => 2],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'imei' => Yii::t('app', 'Imei'),
            'comand' => Yii::t('app', 'Comand'),
            'status' => Yii::t('app', 'Status'),
            'date_sent' => Yii::t('app', 'Date Sent'),
        ];
    }
	
	public function getcom($imei){
		$query = Com::find()->select('*')->where("imei = $imei AND status = '0'")->all();
        return $query;
    }
}
