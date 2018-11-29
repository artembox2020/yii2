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
    const TYPE_PACKET_INITIALIZATION = 1;
    const TYPE_PACKET_DATA = 2;
    const TYPE_PACKET_LOG = 3;
    const TYPE_PACKET_PRICE = 4;

    const TYPE_TIME_OFFSET = 7200;

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
            [['company_id', 'imei_id', 'type_packet'], 'integer'],
            [['imei', 'address'], 'string', 'max' => 250],
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
            'pcb_version' => Yii::t('frontend', 'PCB version'),
            'firmware_version_cpu' => Yii::t('frontend', 'Firmware Version CPU'),
            'firmware_6lowpan' => Yii::t('frontend', 'Link Version'),
            'type_bill_acceptance' => Yii::t('frontend', 'Type Bill Acceptance'),
            'serial_number_kp' => Yii::t('frontend', 'Serial Number Kp')
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
        $jlog->date = Yii::$app->formatter->asDate(time() + self::TYPE_TIME_OFFSET, Imei::DATE_TIME_FORMAT);
        $jlog->save();
    }
    
    /**
     * Gets all type packets
     */
    public static function getTypePackets()
    {
        $typePackets = [
            self::TYPE_PACKET_INITIALIZATION => Yii::t('frontend', 'Initialization'),
            self::TYPE_PACKET_DATA => Yii::t('frontend', 'Status Packages'),
            self::TYPE_PACKET_LOG => Yii::t('frontend', 'Log'),
            self::TYPE_PACKET_PRICE => Yii::t('frontend', 'Price'),
        ];
        
        return $typePackets;
    }
    
    /**
     * @param int $type_packet
     * @return string 
     */
    public static function getTypePacketName($type_packet)
    {
        $typePackets = self::getTypePackets();
        
        return $typePackets[$type_packet];
    }
    
    /**
     * @param string $name
     * @return integer 
     */
    public static function getTypePacketFromName($name)
    {
        $typePackets = self::getTypePackets();
        $typePackets = array_flip($typePackets);
        
        return empty($typePackets[$name]) ? 0 : $typePackets[$name];
    }
    
    /**
     * @param int $name
     * @return array
     */
    public static function getTypePacketsFromNameByStartCondition($name)
    {
        $typePackets = self::getTypePackets();

        if (empty($name)) {
            
            return array_keys($typePackets);
        }

        $packets = [];

        foreach ($typePackets as $index => $typeName)
        {
            if (mb_stripos($typeName, $name) === 0) {
                
                $packets[] = $index;
            }
        }
        
        return $packets;
    }
    
    /**
     * @param string $name
     * @return array 
     */
    public static function getTypePacketsFromNameByEndCondition($name)
    {
        $typePackets = self::getTypePackets();
        
        if (empty($name)) {
            
            return array_keys($typePackets);
        }
        
        $length = strlen($name);
        $packets = [];
        
        foreach ($typePackets as $index => $typeName)
        {
            $typeLength = strlen($typeName);
            
            if (mb_stripos($typeName, $name) === ($typeLength - $length)) {
                
                $packets[] = $index;
            }
        }
        
        return $packets;
    }
    
    /**
     * @param string $name
     * @return array
     */
    public static function getTypePacketsFromNameByContainCondition($name)
    {
        $typePackets = self::getTypePackets();
        
        if (empty($name)) {
            
            return array_keys($typePackets);
        }

        $packets = [];

        foreach ($typePackets as $index => $typeName)
        {
            if (mb_stripos($typeName, $name) !== false) {
                
                $packets[] = $index;
            }
        }
        
        return $packets;
    }
    
    /**
     * @param string $name
     * @return array
     */
    public static function getTypePacketsFromNameByNotContainCondition($name)
    {
        $typePackets = self::getTypePackets();

        if (empty($name)) {
            
            return array_keys($typePackets);
        }

        $packets = [];

        foreach ($typePackets as $index => $typeName)
        {
            if (mb_stripos($typeName, $name) === false) {
                
                $packets[] = $index;
            }
        }
        
        return $packets;
    }
}
