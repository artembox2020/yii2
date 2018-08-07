<?php

namespace frontend\models;
use yii\db\ActiveRecord;
use frontend\services\globals\Entity;
use Yii;

/**
 * This is the model class for table "j_log".
 *
 * @property integer $id
 * @property integer $company_id
 * @property integer $imei_id
 * @property text $packet
 * @property string $type_packet
 * @property string $imei
 * @property string $address
 * @property text $events
 * @property dateTime $date
 */
class Jlog extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'j_log';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['company_id'], 'required'],
            [['company_id', 'imei_id'], 'integer'],
            [['imei', 'address', 'type_packet'], 'string', 'max' => 250],
            [['date'], 'string', 'max' => 128]
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
            'packet' => Yii::t('frontend', 'Packet'),
            'type_packet' => Yii::t('frontend', 'Type Packet'),
            'imei' => Yii::t('frontend', 'Imei'),
            'address' => Yii::t('frontend', 'Address'),
            'date' => Yii::t('frontend', 'DateTime'),
            'events' => Yii::t('frontend', 'Events'),
        ];
    }
    
    /**
     * Creates journal record, based on imei
     * 
     * @param Imei $imei
     * @param string $packet
     * @param string $type_packet
     */
    public function createLogFromImei(Imei $imei, $packet, $type_packet)
    {
        $params = [
            'packet' => $packet,
            'type_packet' => $type_packet,
            'company_id' => $imei->company_id,
            'imei_id' => $imei->id,
            'imei' => $imei->imei,
            'address' => $imei->tryRelationData(['address' => ['address', 'floor'], ', ']),
            'events' => $imei->getLastPing()
        ];
        
        $jlog = new Jlog();
        foreach ($params as $key=>$param)
        {
           if (!empty($param)) {
               $jlog->$key = $param;
           }
        }
        $jlog->date = Yii::$app->formatter->asDate(time(), Imei::DATE_TIME_FORMAT);
        $jlog->save();
    }
}
