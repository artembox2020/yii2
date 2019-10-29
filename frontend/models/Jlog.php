<?php

namespace frontend\models;
use yii\db\ActiveRecord;
use frontend\services\globals\Entity;
use frontend\services\parser\CParser;
use frontend\models\JlogDataCpSearch;
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
    const TYPE_PACKET_DATA_CP = 5;
    const TYPE_PACKET_LOG = 3;
    const TYPE_PACKET_PRICE = 4;
    const TYPE_PACKET_ENCASHMENT = 6;

    const TYPE_TIME_OFFSET = 0;

    const PACKET_DATA_DURATION = 300;
    const PACKET_DATA_TIME_INTERVAL = 1800;

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
            'on_modem_account_number' => Yii::t('frontend', 'On Modem Account/Number'),
            'number_channel' => Yii::t('frontend', 'Number Channel'),
            'pcb_version' => Yii::t('frontend', 'PCB version'),
            'firmware_version_cpu' => Yii::t('frontend', 'Firmware Version CPU'),
            'firmware_version' => Yii::t('frontend', 'Firmware Version'),
            'level_signal' => Yii::t('frontend', 'Level Signal'),
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
            'address' => $imei->tryRelationData(['address' => ['static_address', 'static_floor'], ', ']),
            'events' => $imei->getLastPing()
        ];

        $jlog = new Jlog();
        foreach ($params as $key=>$param)
        {
           if (!empty($param)) {
               $jlog->$key = $param;
           }
        }

        $time = time() + self::TYPE_TIME_OFFSET;

        $jlog->date = Yii::$app->formatter->asDate($time, Imei::DATE_TIME_FORMAT);
        $jlog->date_end = Yii::$app->formatter->asDate($time + self::PACKET_DATA_DURATION, Imei::DATE_TIME_FORMAT);
        $jlog->unix_time_offset = $time;

        // update item if previous item is the same
        if (in_array($params['type_packet'], [self::TYPE_PACKET_DATA ,self::TYPE_PACKET_DATA_CP])) {
            $previousItem = $this->getLastItem($params['imei_id'], $params['type_packet']);

            $previousDate = empty($previousItem) ? false : $previousItem->date_end;
            $previousDate = $previousDate ?? $previousItem->date;

            if (
                !empty($previousDate) &&
                $time - strtotime($previousDate) <= self::PACKET_DATA_TIME_INTERVAL &&
                $this->checkItemsEqual($jlog, $previousItem)
            ) {
                $previousItem->date_end = $jlog->date;

                return $previousItem->update();
            }
        }

        $jlog->save();
    }

    /**
     * Gets last item from `j_log` 
     * 
     * @param integer $imeiId
     * @param integer $type
     * @return Jlog
     */
    public function getLastItem($imeiId, $type)
    {
        $item = Jlog::find()->andWhere(['imei_id' => $imeiId, 'type_packet' => $type])
                             ->orderBy(['id' => SORT_DESC])
                             ->limit(1)
                             ->one();

        return $item;
    }

    /**
     * Checks whether two Jlog items are equal 
     * 
     * @param Jlog $newItem
     * @param Jlog $oldItem
     * @return bool
     */
    public function checkItemsEqual($newItem, $oldItem)
    {
        if ($newItem->packet == $oldItem->packet) {

            return true;
        }

        $cParser = new CParser();
        $newItemImeiData = $cParser->getImeiData($newItem->packet)['imeiData'];
        $oldItemImeiData = $cParser->getImeiData($oldItem->packet)['imeiData'];

        if (!$this->checkItemsByParam($newItemImeiData, $oldItemImeiData, 'in_banknotes')) {

            return false;
        }

        if (!$this->checkItemsByParam($newItemImeiData, $oldItemImeiData, 'money_in_banknotes')) {

            return false;
        }

        if (!$this->checkItemsByParam($newItemImeiData, $oldItemImeiData, 'fireproof_residue')) {

            return false;
        }

        $jlogDataCpSearch = new JLogDataCpSearch();

        if (
            $jlogDataCpSearch->getCPStatusFromDataPacket($newItem->packet)
            != 
            $jlogDataCpSearch->getCPStatusFromDataPacket($oldItem->packet)
        ) {

            return false;
        }

        if (
            $jlogDataCpSearch->getEvtBillValidatorFromDataPacket($newItem->packet)
            !=
            $jlogDataCpSearch->getEvtBillValidatorFromDataPacket($oldItem->packet)
        ) {

            return false;
        }

        if (!$this->checkWmMashinesEqual($oldItem->packet, $newItem->packet)) {

            return false;
        }

        return true;
    }

    /**
     * Checks imeiData items to be equal by parameter 
     * 
     * @param array $imeiData
     * @param array $imeiDataNew
     * @return bool
     */
    public function checkItemsByParam($imeiData, $imeiDataNew, $param)
    {
        if (!isset($imeiData[$param])) {

            if (!isset($imeiDataNew[$param])) {

                return true;
            } else {
                
                return false;
            }
        }

        if (!isset($imeiDataNew[$param])) {

            return false;
        }

        return $imeiDataNew[$param] == $imeiData[$param];
    }

    /**
     * Checks whether data packets 'p' and 'p2' have the same WM Mashines info 
     * 
     * @param string $p
     * @param string $p2
     * @return bool
     */
    public function checkWmMashinesEqual($p, $p2)
    {
        $p = strtoupper($p);
        $p2 = strtoupper($p2);
        $position = strpos($p, '_WM');
        $position2 = strpos($p2, '_WM');

        if ($position) {
            $wm_substring = trim(substr($p, $position));
        }

        if ($position2) {
            $wm_substring2 = trim(substr($p2, $position2));
        }

        if (!$position && !$position2) {

            return true;
        }

        if (!$position || !$position2) {

            return false;
        }

        $comparisonResult = $wm_substring === $wm_substring2;

        if (!$comparisonResult) {
            $parser = new CParser();
            $mashinesInfo = $parser->getMashineData($p)['WM'];
            $mashinesInfo2 = $parser->getMashineData($p2)['WM'];

            if (
                !$this->compareByMashinesInfo($mashinesInfo, $mashinesInfo2) ||
                !$this->compareByMashinesInfo($mashinesInfo2, $mashinesInfo)
            ) {

                return false; 
            }
        }

        return true;
    }

    /**
     * Compare two mashines by their mashines info 
     * 
     * @param array $mashinesInfo
     * @param array $mashinesInfo2
     * @return bool
     */
    private function compareByMashinesInfo($mashinesInfo, $mashinesInfo2)
    {
        foreach ($mashinesInfo as $mashineItem) {

            // indicator whether has the same mashine
            $hasMashine = false; 
            foreach ($mashinesInfo2 as $mashineItem2) {
                if ($mashineItem2[1] == $mashineItem[1]) {
                    if (
                        $mashineItem2[3] != $mashineItem[3] ||
                        $mashineItem2[6] != $mashineItem[6]
                    ) {

                        return false;
                    }
                    $hasMashine = true;
                    break;
                }
            }

            if (!$hasMashine) {

                return false;
            }
        }

        return true;
    }

    /**
     * Gets all type packets
     */
    public static function getTypePackets()
    {
        $typePackets = [
            self::TYPE_PACKET_DATA => Yii::t('frontend', 'Status Packages WM'),
            self::TYPE_PACKET_DATA_CP => Yii::t('frontend', 'Status Packages CP'),
            self::TYPE_PACKET_INITIALIZATION => Yii::t('frontend', 'Initialization'),
            self::TYPE_PACKET_LOG => Yii::t('frontend', 'Log'),
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

    /**
     * @return array
     */
    public static function getPageSizes()
    {

        return [
            10 => 10,
            20 => 20,
            50 => 50,
            100 => 100,
            200 => 200,
            500 => 500
        ];
    }

    /**
     * Makes initialization record by existing item
     * 
     * @param Jlog $item
     * @param int $start
     * @param int $signalLevel
     */
    public function makeInitializationLogByItem($item, $start, $signalLevel)
    {
        $parser = new CParser();
        if ($item && $parser->checkNewInitializationPacket($item->packet)) {
            $oldItem = Jlog::find()
                ->andWhere([
                    'imei_id' => $item->imei_id, 'type_packet' => $item->type_packet, 'unix_time_offset' => $start
                ])
                ->limit(1)
                ->one();

            if ($oldItem) {
                $oldItem->delete();
            }

            $item->packet = $parser->replaceLevelSignal($item->packet, $signalLevel);
            $item->date = Yii::$app->formatter->asDate($start, Imei::DATE_TIME_FORMAT);
            $item->unix_time_offset = $start;
            $item->isNewRecord = true;
            unset($item->id);
            $item->save();
        }
    }

    /**
     * Makes data record by existing item
     * 
     * @param \frontend\models\Jlog $item
     * @param int $start
     * @param int $cpStatus
     * 
     * @return int
     */
    public function makeDataLogByItem($item, $start, $cpStatus)
    {
        $parser = new CParser();
        $oldItem = Jlog::find()
                ->andWhere([
                    'imei_id' => $item->imei_id, 'type_packet' => $item->type_packet, 'unix_time_offset' => $start
                ])
                ->limit(1)
                ->one();

        if ($oldItem) {
            $oldItem->delete();
        }

        $date = Yii::$app->formatter->asDate($start, Imei::DATE_TIME_FORMAT);
        $dateEnd = Yii::$app->formatter->asDate($start + self::PACKET_DATA_DURATION, Imei::DATE_TIME_FORMAT);
        $oldCpStatus = $parser->getCpStatus($item->packet);
        $item->packet = $parser->replaceCpStatus($item->packet, $cpStatus);
        $previousEnd = $item->date_end ? $item->date_end : $item->date;
        $previousEndStamp = strtotime($previousEnd);

        $condition = ($oldCpStatus == $cpStatus) && ($start - $previousEndStamp <= self::PACKET_DATA_TIME_INTERVAL);

        if (
            $condition &&
            ($cpStatus != ImeiData::CP_STATUS_TERMINAL_NOT_IN_TOUCH || $start - $previousEndStamp <= self::PACKET_DATA_DURATION)
        ) {
            $item->date_end = $date;

            return $item->update();
        } else {

            $item->date = $date;
            $item->date_end = $dateEnd;
            $item->unix_time_offset = $start;
            $item->isNewRecord = true;
            unset($item->id);

            return $item->save();
        }
    }
}