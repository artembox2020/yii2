<?php

namespace frontend\controllers;

use frontend\models\dto\MachineDto;
use frontend\services\custom\Debugger;
use yii\web\Controller;

/**
 * $machineDto = new MachineDto($array);
 * $machine = new Machine();
 * $machine->status = $machineDto->status;
 * $machine->save();
 * 
 * Class TestController
 * @package frontend\controllers
 */
class TestController extends Controller
{
    public function actionGet($value)
    {
        echo '<pre>';
        $column_names = [
            'edate',
            'imei',
            'gsmSignal',
            'billModem',
            'numBills',
            'sumBills',
            'ost',
            'price',
            'type',
            'numDev',
            'devSignal',
            'billCash',
            'doorpos',
            'doorled',
            'statusDev',
            'type2',
            'numDev2',
            'devSignal2',
            'billCash2',
            'statusDev2',
            'type3',
            'colGel3',
            'billCash3',
            'statusDev3',
            'type4',
            'colCart4',
            'billCash4',
            'statusDev4'
        ];


        $array = array_map("str_getcsv", explode('*', $value));

        $arrOut = array();
        foreach ($array as $subArr) {
            $arrOut = array_merge($arrOut, $subArr);
        }
        $result = array_combine($column_names, $arrOut);

//        print_r($result);

        $machine = new MachineDto($result);
        var_dump($machine);

    }

    function request_url()
    {
        $result = ''; // Пока результат пуст
        $default_port = 80; // Порт по-умолчанию

        // А не в защищенном-ли мы соединении?
        if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on')) {
            // В защищенном! Добавим протокол...
            $result .= 'https://';
            // ...и переназначим значение порта по-умолчанию
            $default_port = 443;
        } //isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on')
        else {
            // Обычное соединение, обычный протокол
            $result .= 'http://';
        }
        // Имя сервера, напр. site.com или www.site.com
        $result .= $_SERVER['SERVER_NAME'];

        // А порт у нас по-умолчанию?
        if ($_SERVER['SERVER_PORT'] != $default_port) {
            // Если нет, то добавим порт в URL
            $result .= ':' . $_SERVER['SERVER_PORT'];
        } //$_SERVER['SERVER_PORT'] != $default_port
        // Последняя часть запроса (путь и GET-параметры).
        $result .= $_SERVER['REQUEST_URI'];
        // Уфф, вроде получилось!
        return $result;
    }

    public function actionIn($p)
    {
        $url = $this->request_url();

//        Debugger::dd($url);

        //Формируем урл
        $new_url = str_replace('?r=inbox/inbox', '', $url);

        //Парсим его
        parse_str(html_entity_decode($new_url), $out);
        $type = array_keys($out);

        //Определяем какой тип пакета
        $type_p = substr($type[0], strrpos($type[0], '?') + 1);

//        Debugger::dd($new_url);

//        $this->In1($new_url);
        $this->Data1($new_url);
    }

    /**
     * 2017-12-27 13:12:02:32
     * http://test1.ru/index.php/in&p=866104020101005*0.1.33*MDB*7070000435*380937777777*380937777777*2*1
     * @param $data
     */
    public function In1($data)
    {
//        file_put_contents('log/data.log', PHP_EOL . date("Y-m-d H:m:i:s"), FILE_APPEND);
//        file_put_contents('log/data.log', PHP_EOL . $murl, FILE_APPEND);
        foreach ($_GET as $key => $value) {
            if ($key != 'r') {
//                file_put_contents('log/data.log', PHP_EOL . $key . ' : ' . $value, FILE_APPEND);
            } //$key != 'r'
        } //$_GET as $key => $value
//        file_put_contents('log/data.log', PHP_EOL . '---------------------------------------------', FILE_APPEND);
        ////////////////////////////////////////////////
        $murl = $data;
        $err = 0;
        //Теперь опять разбираем данные в пакете

        parse_str(html_entity_decode($murl), $out);

//        Debugger::dd($out);
        $p = $out['http://sens_loc/test/in?p'];
        if ($p == '') {
            $err = 1;
            echo 'Need p!';
            return;
        } //$p == ''

        $param = explode('_', $p);

//        Debugger::dd($param);

        $param1 = explode('*', $param[0]);

        Debugger::dd($param1);

        $imei = $param1[0];
        $fvVer = $param1[1];
        $kpVer = $param1[2];
        $srVer = $param1[3];
        $mTel = $param1[4];
        $sTel = $param1[5];
        $ksum = $param1[6];
        $timeout = $param1[7];
        $date = date("Y-m-d H:i:s");
        //Записываем инициализационные данные в основную таблицу для вывода в мониторинге и остальных пунктах
        $db = new Database();
        $sql = "INSERT INTO base (
                            date, imei, fvVer, kpVer, srVer, mTel, sTel, ksum, timeout
                            )VALUES 
		('$date', '$imei', '$fvVer', '$kpVer', '$srVer', '$mTel', '$sTel', '$ksum', '$timeout')";
        $result = $db->query($sql);

        //Команды для автомата
        $sql = "SELECT `imei` FROM `com` WHERE `imei` = '$imei'";
        $coms = $db->query($sql);

        $return = '';
        foreach ($coms as $com) {
            if ($com->comand != '7') {
                $com->status = '1';
                $com->save();
                if ($return == '') {
                    $return = 'com=' . $com->comand;
                } //$return == ''
                else {
                    $return .= '*' . $com->comand;
                }
                if ($com->comand == '6') {
                    $return .= '&' . time();
                } //$com->comand == '6'
            } //$com->comand != '7'
        } //$coms as $com
        echo $return;

    }

    /**
     * 2018-01-10 14:01:02:36
     * http://95.47.114.243/index.php/data1&p=1515605524*862631033023192*-78*0.00*118*0.00*172618.00*2_WM*1*-71*0.0*1*1*4_WM*2*-70*0.0*1*1*5_WM*3*-68*0.0*1*1*4_WM*4*-76*0.0*0*0*1_WM*5*-73*0.0*0*0*$
     * p : 1515605524*862631033023192*-78*0.00*118*0.00*172618.00*2_WM*1*-71*0.0*1*1*4_WM*2*-70*0.0*1*1*5_WM*3*-68*0.0*1*1*4_WM*4*-76*0.0*0*0*1_WM*5*-73*0.0*0*0*1_GD*5000*0.0*4
     * @param $data
     */
    public function Data1($data)
    {
        $murl = $data;
//        file_put_contents('log/data.log', PHP_EOL . date("Y-m-d H:m:i:s"), FILE_APPEND);
//        file_put_contents('log/data.log', PHP_EOL . $murl, FILE_APPEND);
        foreach ($_GET as $key => $value) {
            if ($key != 'r') {
//                file_put_contents('log/data.log', PHP_EOL . $key . ' : ' . $value, FILE_APPEND);
            } //$key != 'r'
        } //$_GET as $key => $value
//        file_put_contents('log/data.log', PHP_EOL . '---------------------------------------------', FILE_APPEND);
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        $murl = $data;
        $err  = 0;
        //Разбор входящих данных в пакете
        parse_str(html_entity_decode($murl), $out);

        $p = $out['http://sens_loc/test/in?p'];

        if ($p == '') {
            $err = 1;
            echo 'Need p!';
            return;
        } //$p == ''

        $param = explode('_', $p);

        $param1 = explode('*', $param[0]);

//        Debugger::d($param);
//        Debugger::dd($param1);

        $edate     = date("Y-m-d H:i:s");;
        $imei      = $param1[1];
        $gsmSignal = $param1[2];
        $billModem = $param1[3];
        $numBills  = $param1[4];
        $sumBills  = $param1[5];
        $ost       = $param1[6];
        $price     = $param1[7];
        $timeout   = '';


        $i = 0;
        //////////////////////////////////////////////////////////////////////////////////////
        if (count($param) < 2) {
            //Назначаем переменные для подготовки к записи в базу
            $date              = date("Y-m-d H:i:s");
            $fvVer             = '';
            $billAcceptorState = '';
            $id_hard           = $imei . 'CB';
            $collection        = '';
            $ZigBeeSig         = '';
            $billCash          = '';
            $tarif             = '';
            $event             = '';
            $devSignal         = '';
            $statusDev         = '';
            $colGel            = '';
            $colCart           = '';

            //Подготавливаем модель и данные с пакета и переменных для записи в основную таблицу с данными автоматов
            //Если имеи не пустой тогда данные сохраняемм и записываем в базу
//            if ($imei != '') {
//                $db     = new Database();
//                $sql    = "INSERT INTO base
//					(date, imei, gsmSignal, fvVer, numBills, billAcceptorState, id_hard, type, collection, ZigBeeSig,
//					billCash, event, edate, billModem, sumBills, ost, numDev, devSignal, statusDev, colGel)VALUES
//					('$date', '$imei', '$gsmSignal', '$fvVer', '$numBills', '$billAcceptorState', '$id_hard',
//					'$type', '$collection', '$ZigBeeSig', '$billCash',
//					 '$event', '$edate', '$billModem', '$sumBills', '$ost', '$numDev',
//					'$devSignal', '$statusDev', '$colGel')";
//                $result = $db->query($sql);
//            } //$imei != ''
        } //count($param) < 2

        //Повторно разбираем и определяем для событий
        foreach ($param as $element) {

            if ($i > 0) {

                $param2            = explode('*', $element);

                Debugger::dd($param2);
                $type              = $param2[0];
                $numDev            = $param2[1];
                $date              = date("Y-m-d H:i:s");
                $fvVer             = '';
                $billAcceptorState = '';
                $id_hard           = $imei . $type . $numDev;
                $collection        = '';
                $ZigBeeSig         = '';
                $billCash          = '';
                $tarif             = '';
                $event             = '';
                $devSignal         = '';
                $statusDev         = '';
                $colGel            = '';
                $colCart           = '';
                $doorpos           = '';
                $doorled           = '';



                //Определяеем события и данные к ним
                switch ($type) {
                    case 'WM':
                        $devSignal = $param2[2];
                        $billCash  = $param2[3];
                        $doorpos   = $param2[4];
                        $doorled   = $param2[5];
                        $statusDev = $param2[6];
                        break;
                    case 'DM':
                        $devSignal = $param2[2];
                        $billCash  = $param2[3];
                        $statusDev = $param2[4];
                        break;
                    case 'GD':
                        $colGel    = $param2[1];
                        $billCash  = $param2[2];
                        $statusDev = $param2[3];
                        $numDev    = '';
                        break;
                    case 'DC':
                        $colCart   = $param2[1];
                        $billCash  = $param2[2];
                        $statusDev = $param2[3];
                        $numDev    = '';
                } //$type
                //////////////////////////////////
                //Подготавливаем к записи
                //Сохраняем
                if ($imei != '') {
                    $db     = new Database();
                    $sql    = "INSERT INTO base 
					(date, imei, gsmSignal, fvVer, numBills, billAcceptorState, id_hard, type, collection, ZigBeeSig, 
					billCash,  event, edate, billModem, sumBills, ost, numDev, devSignal, 
					statusDev, colGel, colCart, price, timeout, doorpos, doorled)VALUES 
					('$date', '$imei', '$gsmSignal', '$fvVer', '$numBills', '$billAcceptorState', '$id_hard', 
					'$type', '$collection', '$ZigBeeSig', '$billCash', 
					'$event', '$edate', '$billModem', '$sumBills', '$ost', '$numDev', 
					'$devSignal', '$statusDev', '$colGel', '$colCart', '$price', '$timeout', '$doorpos', '$doorled')";
                    $result = $db->query($sql);
                } //$imei != ''
            } //$i > 0
            $i++;
        } //$param as $element
        //Команды
        $sql    = "SELECT `imei` FROM `com` WHERE `imei` = '$imei'";
        $coms   = $db->query($sql);
        $return = '';
        foreach ($coms as $com) {
            if ($com->comand != '7') {
                $com->status = '1';
                $com->save();
                if ($return == '') {
                    $return = 'com=' . $com->comand;
                } //$return == ''
                else {
                    $return .= '*' . $com->comand;
                }
                if ($com->comand == '6') {
                    $return .= '&' . time();
                } //$com->comand == '6'
            } //$com->comand != '7'
        } //$coms as $com
    }
}
