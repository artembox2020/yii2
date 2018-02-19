<?php

namespace frontend\models;
use yii\db\ActiveRecord;
use frontend\models\Devices;
use Yii;

/**
 * This is the model class for table "base".
 *
 * @property integer $id
 * @property string $date
 * @property string $imei
 * @property string $gsmSignal
 * @property string $fvVer
 * @property string $numBills
 * @property string $billAcceptorState
 * @property string $id_hard
 * @property string $type
 * @property string $collection
 * @property string $ZigBeeSig
 * @property string $billCash
 * @property string $tariff
 * @property string $event
 * @property string $edate
 * @property string $billModem
 * @property string $sumBills
 * @property string $ost
 * @property string $numDev
 * @property string $devSignal
 * @property string $statusDev
 * @property string $colGel
 * @property string $colCart
 * @property string $price
 * @property string $timeout
 * @property string $doorpos
 * @property string $doorled
 * @property string $kpVer
 * @property string $srVer
 * @property string $mTel
 * @property string $sTel
 * @property string $ksum
 */
class Base extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'base';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date', 'edate'], 'safe'],
            [['imei', 'numBills', 'billCash', 'tariff', 'kpVer', 'srVer', 'mTel', 'sTel', 'ksum'], 'string', 'max' => 100],
            [['gsmSignal', 'event'], 'string', 'max' => 20],
            [['fvVer', 'billAcceptorState', 'type', 'billModem', 'sumBills', 'ost', 'numDev', 'devSignal', 'statusDev', 'colGel', 'colCart', 'price', 'timeout', 'doorpos', 'doorled'], 'string', 'max' => 50],
            [['id_hard'], 'string', 'max' => 200],
            [['collection'], 'string', 'max' => 2],
            [['ZigBeeSig'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'date' => Yii::t('app', 'Date'),
            'imei' => Yii::t('app', 'Imei'),
            'gsmSignal' => Yii::t('app', 'Gsm Signal'),
            'fvVer' => Yii::t('app', 'Fv Ver'),
            'numBills' => Yii::t('app', 'Num Bills'),
            'billAcceptorState' => Yii::t('app', 'Bill Acceptor State'),
            'id_hard' => Yii::t('app', 'Id Hard'),
            'type' => Yii::t('app', 'Type'),
            'collection' => Yii::t('app', 'Collection'),
            'ZigBeeSig' => Yii::t('app', 'Zig Bee Sig'),
            'billCash' => Yii::t('app', 'Bill Cash'),
            'tariff' => Yii::t('app', 'Tariff'),
            'event' => Yii::t('app', 'Event'),
            'edate' => Yii::t('app', 'Edate'),
            'billModem' => Yii::t('app', 'Bill Modem'),
            'sumBills' => Yii::t('app', 'Sum Bills'),
            'ost' => Yii::t('app', 'Ost'),
            'numDev' => Yii::t('app', 'Num Dev'),
            'devSignal' => Yii::t('app', 'Dev Signal'),
            'statusDev' => Yii::t('app', 'Status Dev'),
            'colGel' => Yii::t('app', 'Col Gel'),
            'colCart' => Yii::t('app', 'Col Cart'),
            'price' => Yii::t('app', 'Price'),
            'timeout' => Yii::t('app', 'Timeout'),
            'doorpos' => Yii::t('app', 'Doorpos'),
            'doorled' => Yii::t('app', 'Doorled'),
            'kpVer' => Yii::t('app', 'Kp Ver'),
            'srVer' => Yii::t('app', 'Sr Ver'),
            'mTel' => Yii::t('app', 'M Tel'),
            'sTel' => Yii::t('app', 'S Tel'),
            'ksum' => Yii::t('app', 'Ksum'),
        ];
    }
	
	public function gett($imei){
		$query = Base::find();
		$get = $query
				->where("`imei` = '".$imei."'")
				->limit(1)
				->distinct()
				->all();
		return $get;
    }
	
	public static function get_adress($adress){
		$query = Devices::find();
		$get = $query
				->where("`adress` = '".$adress."'")
				->all();
		return $get;
    }
    
    public static function getMonitor($id_dev = 0){
		$query = Base::find();
		$get = $query
				->where("`billModem` <> 'NULL' AND `imei` = '".$id_dev."'")
				->orderBy('edate')
				->distinct()
				->all();
		return $get;
    }
    
    
    public function getEdate($imei){
		$query = Base::find();
		$get = $query
				->where("`imei` = '".$imei."'")
				->orderBy('edate')
				->limit(1)
				->all();
		return $get;
    }
    
    public static function getTypes($imei, $edate){
		$query = Base::find() 
		  ->select('*')
          ->where("`imei` = '".$imei."'")
		  ->andWhere("edate = '".$edate."'")
          ->asArray()
		  ->distinct()
		  //->orderBy('edate')
		  ->limit(10)
          ->all();
		  
		return $query;
        
    }
    
    public static function getVer($imei){
		$query = Base::find();
		$get = $query
				->where("`imei` = '".$imei."' AND `fvVer` <> ''")
				->orderBy('date')
				->distinct()
				->all();
		return $get;
    }
    
    public static function nameStatus($type = '', $event = 0){
        
        $name = 'undefined event';
        switch ($type){
            case 'WM':
                switch ($event){
                    case '-2':
                        $name = Yii::t('UserModule.events', 'Обнуление') ;
                    break;
                    case '-1':
                        $name = Yii::t('UserModule.events', 'Пополнение') ;
                    break;
                    case '0':
                        $name = Yii::t('UserModule.events', 'Нет связи ') ;
                    break;
                    case '1':
                        $name = Yii::t('UserModule.events', 'К работе готова!') ;
                    break;
                    case '2':
                        $name = Yii::t('UserModule.events', 'Ожидание начало работы') ;
                    break;
                    case '3':
                        $name = Yii::t('UserModule.events', 'Устройство занято') ;
                    break;
                    case '4':
                        $name = Yii::t('UserModule.events', 'Стирка') ;
                    break;
                    case '5':
                        $name = Yii::t('UserModule.events', 'Полоскание') ;
                    break;
                    case '6':
                        $name = Yii::t('UserModule.events', 'Отжим') ;
                    break;
                    case '7':
                        $name = Yii::t('UserModule.events', 'Розблокирование дверцы') ;
                    break;
                    case '8':
                        $name = Yii::t('UserModule.events', 'Конец цикла работы') ;
                    break;
                    case '9':
                        $name = Yii::t('UserModule.events', 'Ошибка') ;
                    break;
                    case '10':
                        $name = Yii::t('UserModule.events', 'Ошибка датчика воды') ;
                    break;
                    case '11':
                        $name = Yii::t('UserModule.events', 'Ошибка датчика холла мотора') ;
                    break;
                    case '12':
                        $name = Yii::t('UserModule.events', 'Ошибка подачи воды') ;
                    break;
                    case '13':
                        $name = Yii::t('UserModule.events', 'Ошибка слива') ;
                    break;
                    case '14':
                        $name = Yii::t('UserModule.events', 'Ошибка мотора') ;
                    break;
                    case '15':
                        $name = Yii::t('UserModule.events', 'Ошибка напряжения питания') ;
                    break;
                    case '16':
                        $name = Yii::t('UserModule.events', 'Ошибка связи') ;
                    break;
                    case '17':
                        $name = Yii::t('UserModule.events', 'Обозначает включения (bE)') ;
                    break;
                    case '18':
                        $name = Yii::t('UserModule.events', 'Ошибка охлаждения (cE)') ;
                    break;
                    case '19':
                        $name = Yii::t('UserModule.events', 'Ошибка закрытия дверцы (dE)') ;
                    break;
                    case '20':
                        $name = Yii::t('UserModule.events', 'Ошибка вентиляции (fE)') ;
                    break;
                    case '21':
                        $name = Yii::t('UserModule.events', 'Ошибка нагревателя (hE)') ;
                    break;
                    case '22':
                        $name = Yii::t('UserModule.events', 'Ошибка утечка воды (lE)') ;
                    break;
                    case '23':
                        $name = Yii::t('UserModule.events', 'Ошибка перелива воды') ;
                    break;
                    case '24':
                        $name = Yii::t('UserModule.events', 'Ошибка датчика температуры') ;
                    break;
                    case '25':
                        $name = Yii::t('UserModule.events', 'Ошибка дисбаланса') ;
                    break;
                    case '26':
                        $name = Yii::t('UserModule.events', 'Ошибки переполнены') ;
                    break;             
                }
            break;
            case 'DM':
                switch ($event){
                    case '-2':
                        $name = Yii::t('UserModule.events', 'Обнуление') ;
                    break;                
                    case '-1':
                        $name = Yii::t('UserModule.events', 'Пополнение') ;
                    break;
                    case '0':
                        $name = Yii::t('UserModule.events', 'Нет связи ') ;
                    break;
                    case '1':
                        $name = Yii::t('UserModule.events', 'К работе готова!') ;
                    break;
                    case '2':
                        $name = Yii::t('UserModule.events', 'Ожидание начало работы') ;
                    break;
                    case '3':
                        $name = Yii::t('UserModule.events', 'Устройство занято') ;
                    break;
                    case '4':
                        $name = Yii::t('UserModule.events', 'Режим Сушка') ;
                    break;
                    case '5':
                        $name = Yii::t('UserModule.events', 'Сушка Завершена') ;
                    break;
                }
            break;
            case 'GD':
                switch ($event){
                    case '0':
                        $name = Yii::t('UserModule.events', 'Нет связи ') ;
                    break;
                    case '1':
                        $name = Yii::t('UserModule.events', 'Устройство подключено ') ;
                    break;
                    case '2':
                        $name = Yii::t('UserModule.events', 'К работе готова!') ;
                    break;
                    case '3':
                        $name = Yii::t('UserModule.events', 'Подача геля') ;
                    break;
                    case '4':
                        $name = Yii::t('UserModule.events', 'Недостаточно денег на счету') ;
                    break;
                    case '5':
                        $name = Yii::t('UserModule.events', 'Замена ёмкости геля') ;
                    break;
                    case '6':
                        $name = Yii::t('UserModule.events', 'Закончился гель') ;
                    break;
                    case '7':
                        $name = Yii::t('UserModule.events', 'Ошибка в работе датчика потока или насоса') ;
                    break;
                    case '8':
                        $name = Yii::t('UserModule.events', 'Ошибка в параметрах') ;
                    break;
                }
            break;
            case 'DC':
                switch ($event){
                    case '-2':
                        $name = Yii::t('UserModule.events', 'Обнуление') ;
                    break;
                    case '-1':
                        $name = Yii::t('UserModule.events', 'Пополнение') ;
                    break;
                    case '0':
                        $name = Yii::t('UserModule.events', 'Нет связи') ;
                    break;
                    case '1':
                        $name = Yii::t('UserModule.events', 'К работе готова!') ;
                    break;
                    case '2':
                        $name = Yii::t('UserModule.events', 'Ожидание начало работы') ;
                    break;
                    case '3':
                        $name = Yii::t('UserModule.events', 'Устройство занято') ;
                    break;
                    case '4':
                        $name = Yii::t('UserModule.events', 'Карта активирована') ;
                    break;              
                }
            break;
        }
        return $name;
    }       
    
    
    
    
    public static function nameEvent($type = '', $event = 0){
        
        //$name = 'undefined event';
        switch ($type){
            case 'CB':
                switch ($event){
                    case '-11':
                        $name = Yii::t('UserModule.events', 'INVALID_COMMAND') ;
                    break;
                    case '-10':
                        $name = Yii::t('UserModule.events', 'BILL_REJECT') ;
                    break;
                    case '-9':
                        $name = Yii::t('UserModule.events', 'STACKER_PROBLEM') ;
                    break;
                    case '-8':
                        $name = Yii::t('UserModule.events', 'BILL_FISH') ;
                    break;
                    case '-7':
                        $name = Yii::t('UserModule.events', 'SENSOR_PROBLEM') ;
                    break;
                    case '-6':
                        $name = Yii::t('UserModule.events', 'BILL_REMOVE') ;
                    break;
                    case '-5':
                        $name = Yii::t('UserModule.events', 'BILL_JAM') ;
                    break;
                    case '-4':
                        $name = Yii::t('UserModule.events', 'CHECKSUM_ERROR') ;
                    break;
                    case '-3':
                        $name = Yii::t('UserModule.events', 'MOTOR_FAILURE') ;
                    break;
                    case '-2':
                        $name = Yii::t('UserModule.events', 'COMERROR') ;
                    break;
                    case '-1':
                        $name = Yii::t('UserModule.events', 'CPU_PROBLEM') ;
                    break;
                    case '0':
                        $name = Yii::t('UserModule.events', 'LINK_PC') ;
                    break;
                    case '1':
                        $name = Yii::t('UserModule.events', 'UPDATE_SOFTWARE') ;
                    break;
                    case '2':
                        $name = Yii::t('UserModule.events', 'CHANGE_TECHNICAL') ;
                    break;
                    case '3':
                        $name = Yii::t('UserModule.events', 'CHANGE_ECONOMICAL') ;
                    break;
                    case '4':
                        $name = Yii::t('UserModule.events', 'CHANGE_REMOTER') ;
                    break;
                    case '5':
                        $name = Yii::t('UserModule.events', 'REQUEST_FROM_SERVER') ;
                    break;
                    case '6';
                        $name = Yii::t('UserModule.events', 'TIME_CORRECTION') ;
                    break;
                    case '7':
                        $name = Yii::t('UserModule.events', 'FULL_BILL_ACCEPTOR') ;
                    break;
                    case '8':
                        $name = Yii::t('UserModule.events', 'COLLECTION') ;
                    break;
                    case '9':
                        $name = Yii::t('UserModule.events', 'TECHNICAL_BILL') ;
                    break;
                    case '10':
                        $name = Yii::t('UserModule.events', 'ACTIVATION_CARD') ;
                    break;
                    case '11':
                        $name = Yii::t('UserModule.events', 'DATA_CARD') ;
                    break;
                    case '12':
                        $name = Yii::t('UserModule.events', 'CASH_CARD') ;
                    break;
                    case '13':
                        $name = Yii::t('UserModule.events', 'START_BOARD') ;
                    break;
                    case '14':
                        $name = Yii::t('UserModule.events', 'UNLINK_PC') ;
                    break;
                    case '15':
                        $name = Yii::t('UserModule.events', 'REPAIR') ;
                    break;
                    case '16':
                        $name = Yii::t('UserModule.events', 'OPEN_DOOR') ;
                    break;
                }
            break;   
            case 'WM':
                switch ($event){
                    case '-19':
                        $name = Yii::t('UserModule.events', 'ERROR_UE') ;
                    break;
                    case '-18':
                        $name = Yii::t('UserModule.events', 'ERROR_tE') ;
                    break;
                    case '-17':
                        $name = Yii::t('UserModule.events', 'ERROR_OE_OF') ;
                    break;
                    case '-16':
                        $name = Yii::t('UserModule.events', 'ERROR_LE') ;
                    break;
                    case '-15':
                        $name = Yii::t('UserModule.events', 'ERROR_HE') ;
                    break;
                    case '-14':
                        $name = Yii::t('UserModule.events', 'ERROR_FE') ;
                    break;
                    case '-13':
                        $name = Yii::t('UserModule.events', 'ERROR_dE') ;
                    break;
                    case '-12':
                        $name = Yii::t('UserModule.events', 'ERROR_CE') ;
                    break;
                    case '-11':
                        $name = Yii::t('UserModule.events', 'ERROR_bE') ;
                    break;
                    case '-10':
                        $name = Yii::t('UserModule.events', 'ERROR_AE') ;
                    break;
                    case '-9':
                        $name = Yii::t('UserModule.events', 'ERROR_9E_Uc') ;
                    break;
                    case '-8':
                        $name = Yii::t('UserModule.events', 'ERROR_8E') ;
                    break;
                    case '-7':
                        $name = Yii::t('UserModule.events', 'ERROR_5E') ;
                    break;
                    case '-6':
                        $name = Yii::t('UserModule.events', 'ERROR_4E') ;
                    break;
                    case '-5':
                        $name = Yii::t('UserModule.events', 'ERROR_3E') ;
                    break;
                    case '-4':
                        $name = Yii::t('UserModule.events', 'ERROR_1E') ;
                    break;
                    case '-3':
                        $name = Yii::t('UserModule.events', 'ZERO_WORK') ;
                    break;
                    case '-2':
                        $name = Yii::t('UserModule.events', 'FREEZE_WITH_WATER') ;
                    break;
                    case '-1':
                        $name = Yii::t('UserModule.events', 'NO_CONNECT_MCD') ;
                    break;
                    case '0':
                        $name = Yii::t('UserModule.events', 'NO_POWER') ;
                    break;
                    case '1':
                        $name = Yii::t('UserModule.events', 'POWER_ON_WASHER') ;
                    break;
                    case '2':
                        $name = Yii::t('UserModule.events', 'REFILL_WASHER') ;
                    break;
                    case '3':
                        $name = Yii::t('UserModule.events', 'WASHING_DRESS') ;
                    break;
                    case '4':
                        $name = Yii::t('UserModule.events', 'RISING_DRESS') ;
                    break;
                    case '5':
                        $name = Yii::t('UserModule.events', 'EXTRACTION_DRESS') ;
                    break;
                    case '6':
                        $name = Yii::t('UserModule.events', 'WASHING_END') ;
                    break;
                    case '7':
                        $name = Yii::t('UserModule.events', 'WASHER_FREE') ;
                    break;
                    case '8':
                        $name = Yii::t('UserModule.events', 'NULLING_WASHER') ;
                    break;
                    case '9':
                        $name = Yii::t('UserModule.events', 'CONNECT_MCD') ;
                    break;
                    case '10':
                        $name = Yii::t('UserModule.events', 'SUB_BY_WORK') ;
                    break;
                    case '11':
                        $name = Yii::t('UserModule.events', 'MAX_WASHER_EVENT') ;
                    break;                
                }
            break;
            case 'DM':
                switch ($event){
                    case '-1':
                        $name = Yii::t('UserModule.events', 'ZERO_DRYER') ;
                    break;
                    case '0':
                        $name = Yii::t('UserModule.events', 'REFILL_DRYER') ;
                    break;
                    case '1':
                        $name = Yii::t('UserModule.events', 'DRYING') ;
                    break;
                    case '2':
                        $name = Yii::t('UserModule.events', 'DRYING_END') ;
                    break;
                    case '3':
                        $name = Yii::t('UserModule.events', 'DRYER_FREE') ;
                    break;
                    case '4':
                        $name = Yii::t('UserModule.events', 'NULLING_DRYER') ;
                    break;
                    case '5':
                        $name = Yii::t('UserModule.events', 'MAX_DRYER_EVENT') ;
                    break;
                }
            break;
            case 'CM':
                switch ($event){
                    case '-5':
                        $name = Yii::t('UserModule.events', 'ERR_SENSOR') ;
                    break;
                    case '-4':
                        $name = Yii::t('UserModule.events', 'ERR_PARAM') ;
                    break;
                    case '-3':
                        $name = Yii::t('UserModule.events', 'ERR_NO_FLOW') ;
                    break;
                    case '-2':
                        $name = Yii::t('UserModule.events', 'ZERO_CONDITIONER') ;
                    break;
                    case '-1':
                        $name = Yii::t('UserModule.events', 'ZERO_POWDER') ;
                    break;
                    case '0':
                        $name = Yii::t('UserModule.events', 'NO_POWER_CLEANER') ;
                    break;
                    case '1':
                        $name = Yii::t('UserModule.events', 'POWER_ON_CLEANER') ;
                    break;
                    case '2':
                        $name = Yii::t('UserModule.events', 'REFILL_CLEANER') ;
                    break;
                    case '3':
                        $name = Yii::t('UserModule.events', 'ISSUING_POWDER') ;
                    break;
                    case '4':
                        $name = Yii::t('UserModule.events', 'ISSUING_CONDITIONER') ;
                    break;
                    case '5':
                        $name = Yii::t('UserModule.events', 'NULLING_CLEANER') ;
                    break;
                    case '6':
                        $name = Yii::t('UserModule.events', 'CHANGE_POWDER') ;
                    break;
                    case '7':
                        $name = Yii::t('UserModule.events', 'CHANGE_CONDITIONER') ;
                    break;
                    case '8':
                        $name = Yii::t('UserModule.events', 'CALIBR_POWDER') ;
                    break;
                    case '9':
                        $name = Yii::t('UserModule.events', 'CALIBR_CONDITIONER') ;
                    break;
                    case '10':
                        $name = Yii::t('UserModule.events', 'MAX_CLEANER_EVENT') ;
                    break;
                }
            break;
            case 'UC':
                switch ($event){
                    case '0':
                        $name = Yii::t('UserModule.events', 'CARDING') ;
                    break;
                    case '1':
                        $name = Yii::t('UserModule.events', 'MAX_UNITCARDS_EVENT') ;
                    break;
                }
            break;
        }
        return $name;
    }
}
