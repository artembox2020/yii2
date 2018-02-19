<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "devices".
 *
 * @property integer $id
 * @property string $id_dev
 * @property string $name
 * @property string $organization
 * @property string $city
 * @property string $adress
 * @property string $name_cont
 * @property string $tel_cont
 * @property string $operator
 * @property string $n_operator
 * @property string $kp
 * @property string $kps
 * @property string $balans
 */
class Devices extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'devices';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_dev', 'operator', 'n_operator', 'kp', 'balans'], 'string', 'max' => 100],
            [['name', 'organization', 'city', 'adress', 'name_cont'], 'string', 'max' => 250],
            [['tel_cont', 'kps'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_dev' => Yii::t('app', 'Id Dev'),
            'name' => Yii::t('app', 'Name'),
            'organization' => Yii::t('app', 'Organization'),
            'city' => Yii::t('app', 'City'),
            'adress' => Yii::t('app', 'Adress'),
            'name_cont' => Yii::t('app', 'Name Cont'),
            'tel_cont' => Yii::t('app', 'Tel Cont'),
            'operator' => Yii::t('app', 'Operator'),
            'n_operator' => Yii::t('app', 'N Operator'),
            'kp' => Yii::t('app', 'Kp'),
            'kps' => Yii::t('app', 'Kps'),
            'balans' => Yii::t('app', 'Balans'),
        ];
    }
	
	public function getBase()
    {
        return $this->hasMany(Base::className(), ['imei' => 'id_dev']);
    }
	
	public function getData(){
		$query = Devices::find() 
		  ->select('*')
          ->joinWith('base')
          ->asArray()
		  //->distinct()
		  ->orderBy('edate')
		  ->limit(10)
          ->all();
		  //var_dump($query);
		return $query;
		
	}
	
	
	
	public function get_d_imei(){
        $query = Devices::find()->select('*')->distinct()->all();
        return $query;
    }
    
    public function all_dev(){
		$query = Devices::find()->select('*')->all();
        return $query;
    }

    public function get_by_id($id){
		$query = Devices::find()->select('*')->where("id = '" . $id . "'")->delete();
        return $query;
    }

    public static function getRezim($rezim){
        if($rezim == '') return '';
        $return = '';
        switch ($rezim){
            case -1:
                $return = 'не выбран';
            break;
            case 0:
                $return = 'коттон';
            break;
            case 1:
                $return = 'синтетика';
            break;
            case 2:
                $return = 'шерсть деликатная';
            break;
            case 3:
                $return = 'быстрая стирка';
            break;
            case 4:
                $return = 'полоскание и отжим';
            break;
            case 5:
                $return = 'отжим';
            break;
            case 6:
                $return = '';
            break;
            case 7:
                $return = 'интенсив';
            break;
            case 8:
                $return = 'задержка стирки';
            break;
            case 9:
                $return = '';
            break;
            case 10:
                $return = '';
            break;
            case 11:
                $return = '';
            break;
        }
        return $return;
    }

    public static function getTemp($rezim){
        if($rezim == '') return '';
        $return = '';
        switch ($rezim){
            case -1:
                $return = 'не выбран';
            break;
            case 0:
                $return = '30C';
            break;
            case 1:
                $return = '40C';
            break;
            case 2:
                $return = '60C';
            break;
            case 3:
                $return = '95C';
            break;
            case 4:
                $return = '';
            break;
            case 5:
                $return = '';
            break;
        }
        return $return;
    }
    public static function getOtzim($rezim){
        if($rezim == '') return '';
        $return = '';
        switch ($rezim){
            case -1:
                $return = 'не выбран';
            break;
            case 0:
                $return = 'без отжима';
            break;
            case 1:
                $return = '400 об';
            break;
            case 2:
                $return = '800 об';
            break;
            case 3:
                $return = '800+ об';
            break;
            case 4:
                $return = '';
            break;
            case 5:
                $return = '';
            break;
        }
        return $return;
    }

    public static function getSob($device, $rezim){
        if($rezim == '') return '';
        if($device == '') return '';
        $return = '';

        if ($device == 'CB'){
            switch ($rezim){
                case -11:
                    $return = 'Ошибочное состояние';
                break;
                case -10:
                    $return = 'Возврат купюры';
                break;
                case -9:
                    $return = 'Проблемы с купюроукладчиком';
                break;
                case -8:
                    $return = 'BILL_FISH';
                break;
                case -7:
                    $return = 'Проблемы с сенсором КП';
                break;
                case -6:
                    $return = 'BILL_REMOVE';
                break;
                case -5:
                    $return = 'BILL_JAM';
                break;
                case -4:
                    $return = 'Ошибка контрольной суммы в КП';
                break;
                case -3:
                    $return = 'Ошибка двигателя КП';
                break;
                case -2:
                    $return = 'COMERROR';
                break;
                case -1:
                    $return = 'Проблемы с CPU ';
                break;
                case 0:
                    $return = 'Подключение ЦП к компьютеру';
                break;
                case 1:
                    $return = 'Обновление системы на центральной плате';
                break;
                case 2:
                    $return = 'CHANGE_TECHNICAL';
                break;
                case 3:
                    $return = 'CHANGE_ECONOMICAL';
                break;
                case 4:
                    $return = 'CHANGE_REMOTER';
                break;
                case 5:
                    $return = 'REQUEST_FROM_SERVER';
                break;
                case 6:
                    $return = 'Коррекция времени ЦП';
                break;
                case 7:
                    $return = 'Купюроукладчик переполнен';
                break;
                case 8:
                    $return = 'Инкасация';
                break;
                case 9:
                    $return = 'Технические деньги';
                break;
                case 10:
                    $return = 'Активация карты';
                break;
                case 11:
                    $return = 'Данные карты';
                break;
                case 12:
                    $return = 'Деньги на карте';
                break;
                case 13:
                    $return = 'Включение ЦП';
                break;
                case 14:
                    $return = 'Отключение ЦП от компьютера';
                break;
                case 15:
                    $return = 'Ремонт';
                break;
                case 16:
                    $return = 'Открыта дверца СМ';
                break;
                case 17:
                    $return = 'Зачисленные деньги';
                    break;
            }
        }

        if ($device == 'WM'){
            switch ($rezim){
                case -19:
                    $return = 'Ошибка дисбаланса';
                break;
                case -18:
                    $return = 'Ошибка датчика температуры';
                break;
                case -17:
                    $return = 'Ошибка перелива воды';
                break;
                case -16:
                    $return = 'Ошибка утечка воды (lE)';
                break;
                case -15:
                    $return = 'Ошибка нагревателя (hE)';
                break;
                case -14:
                    $return = 'Ошибка вентиляции (fE)';
                break;
                case -13:
                    $return = 'Ошибка закрытия дверцы (dE)';
                break;
                case -12:
                    $return = 'Ошибка охлаждения (cE)';
                break;
                case -11:
                    $return = 'Обозначает включения (bE)';
                break;
                case -10:
                    $return = 'Ошибка связи';
                break;
                case -9:
                    $return = 'Ошибка напряжения питания';
                break;
                case -8:
                    $return = 'Ошибка мотора';
                break;
                case -7:
                    $return = 'Ошибка слива';
                break;
                case -6:
                    $return = 'Ошибка подачи воды';
                break;
                case -5:
                    $return = 'Ошибка датчика холла мотора';
                break;
                case -4:
                    $return = 'Ошибка датчика воды';
                break;
                case -3:
                    $return = 'ZERO_WORK';
                break;
                case -2:
                    $return = 'Остановка с водой';
                break;
                case -1:
                    $return = 'Нет связи с МКД';
                break;
                case 0:
                    $return = 'Нет напряжения питания';
                break;
                case 1:
                    $return = 'Подача питания';
                break;
                case 2:
                    $return = 'Пополнение счета ';
                break;
                case 3:
                    $return = 'Стирка';
                break;
                case 4:
                    $return = 'Полоскание';
                break;
                case 5:
                    $return = 'Отжим';
                break;
                case 6:
                    $return = 'Окончание режима стирки';
                break;
                case 7:
                    $return = 'Бесплатная стирка';
                break;
                case 8:
                    $return = 'Обнуление';
                break;
                case 9:
                    $return = 'Подключено MКД';
                break;
                case 10:
                    $return = 'SUB_BY_WORK';
                break;
                case 11:
                    $return = 'MAX_WASHER_EVENT';
                break;
            }
        }
        if ($device == 'DM'){
            switch ($rezim){
                case -1:
                    $return = 'ZERO_DRYER ';
                break;
                case 0:
                    $return = 'Пополнение счета ';
                break;
                case 1:
                    $return = 'Сушка';
                break;
                case 2:
                    $return = 'Окончание сушки';
                break;
                case 3:
                    $return = 'Бесплатная сушка';
                break;
                case 4:
                    $return = 'Обнуление';
                break;
                case 5:
                    $return = 'MAX_DRYER_EVENT';
                break;
             }
        }

       if ($device == 'GD'){
            switch ($rezim){
                case -5:
                    $return = 'ошибка работы датчика или насоса';
                break;
                case -4:
                    $return = 'ошибка в параметрах';
                break;
                case -3:
                    $return = 'закончился гель (меньше уст. в параметрах)';
                break;
                case -2:
                    $return = 'ZERO_CONDITIONER ';
                break;
                case -1:
                    $return = 'выдали геля на все деньги';
                break;
                case 0:
                    $return = 'NO_POWER_CLEANER ';
                break;
                case 1:
                    $return = 'POWER_ON_CLEANER';
                break;
                case 2:
                    $return = 'REFILL_CLEANER';
                break;
                case 3:
                    $return = 'пополнение счета';
                break;
                case 4:
                    $return = 'отпустили кнопку Пуск но есть деньги на счету';
                break;
                case 5:
                    $return = 'NULLING_CLEANER';
                break;
                case 6:
                    $return = 'обнуление счета';
                break;
                case 7:
                    $return = 'замена емкости';
                break;
                case 8:
                    $return = 'CALIBR_POWDER';
                break;
                case 9:
                    $return = 'калибровка дозатора геля';
                break;
                case 10:
                    $return = 'MAX_CLEANER_EVENT';
                break;
            }
        }
       if ($device == 'DC'){
            switch ($rezim){
                case 0:
                    $return = 'Использование карты';
                break;
                case 1:
                    $return = 'MAX_UNITCARDS_EVENT';
                break;
            }
        }


        return $return;
    }
}
