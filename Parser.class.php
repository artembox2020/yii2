<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
require __DIR__ . '/Database.php';
class Parser {
    
    function my_strip_text($text) {
        $text = strip_tags($text);
        $text = addslashes($text);
        $text = str_replace('>', ' ', $text);
        $text = str_replace('<', ' ', $text);
        
        return $text;
    }
    
    function request_url() {
        $result       = ''; // Пока результат пуст
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
    
    function Index() {
        
    }
    
    //Обработка событий состояния автоматов
    function nameEvent($type = '', $event = 0) {
        
        $name = 'undefined event';
        switch ($type) {
            case 'CB':
                switch ($event) {
                    case '-11':
                        $name = 'INVALID_COMMAND';
                        break;
                    case '-10':
                        $name = 'BILL_REJECT';
                        break;
                    case '-9':
                        $name = 'STACKER_PROBLEM';
                        break;
                    case '-8':
                        $name = 'BILL_FISH';
                        break;
                    case '-7':
                        $name = 'SENSOR_PROBLEM';
                        break;
                    case '-6':
                        $name = 'BILL_REMOVE';
                        break;
                    case '-5':
                        $name = 'BILL_JAM';
                        break;
                    case '-4':
                        $name = 'CHECKSUM_ERROR';
                        break;
                    case '-3':
                        $name = 'MOTOR_FAILURE';
                        break;
                    case '-2':
                        $name = 'COMERROR';
                        break;
                    case '-1':
                        $name = 'CPU_PROBLEM';
                        break;
                    case '0':
                        $name = 'LINK_PC';
                        break;
                    case '1':
                        $name = 'UPDATE_SOFTWARE';
                        break;
                    case '2':
                        $name = 'CHANGE_TECHNICAL';
                        break;
                    case '3':
                        $name = 'CHANGE_ECONOMICAL';
                        break;
                    case '4':
                        $name = 'CHANGE_REMOTER';
                        break;
                    case '5':
                        $name = 'REQUEST_FROM_SERVER';
                        break;
                    case '6';
                        $name = 'TIME_CORRECTION';
                        break;
                    case '7':
                        $name = 'FULL_BILL_ACCEPTOR';
                        break;
                    case '8':
                        $name = 'COLLECTION';
                        break;
                    case '9':
                        $name = 'TECHNICAL_BILL';
                        break;
                    case '10':
                        $name = 'ACTIVATION_CARD';
                        break;
                    case '11':
                        $name = 'DATA_CARD';
                        break;
                    case '12':
                        $name = 'CASH_CARD';
                        break;
                    case '13':
                        $name = 'START_BOARD';
                        break;
                    case '14':
                        $name = 'UNLINK_PC';
                        break;
                    case '15':
                        $name = 'REPAIR';
                        break;
                    case '16':
                        $name = 'OPEN_DOOR';
                        break;
                } //$event
                break;
            case 'WM':
                switch ($event) {
                    case '-19':
                        $name = 'ERROR_UE';
                        break;
                    case '-18':
                        $name = 'ERROR_tE';
                        break;
                    case '-17':
                        $name = 'ERROR_OE_OF';
                        break;
                    case '-16':
                        $name = 'ERROR_LE';
                        break;
                    case '-15':
                        $name = 'ERROR_HE';
                        break;
                    case '-14':
                        $name = 'ERROR_FE';
                        break;
                    case '-13':
                        $name = 'ERROR_dE';
                        break;
                    case '-12':
                        $name = 'ERROR_CE';
                        break;
                    case '-11':
                        $name = 'ERROR_bE';
                        break;
                    case '-10':
                        $name = 'ERROR_AE';
                        break;
                    case '-9':
                        $name = 'ERROR_9E_Uc';
                        break;
                    case '-8':
                        $name = 'ERROR_8E';
                        break;
                    case '-7':
                        $name = 'ERROR_5E';
                        break;
                    case '-6':
                        $name = 'ERROR_4E';
                        break;
                    case '-5':
                        $name = 'ERROR_3E';
                        break;
                    case '-4':
                        $name = 'ERROR_1E';
                        break;
                    case '-3':
                        $name = 'ZERO_WORK';
                        break;
                    case '-2':
                        $name = 'FREEZE_WITH_WATER';
                        break;
                    case '-1':
                        $name = 'NO_CONNECT_MCD';
                        break;
                    case '0':
                        $name = 'NO_POWER';
                        break;
                    case '1':
                        $name = 'POWER_ON_WASHER';
                        break;
                    case '2':
                        $name = 'REFILL_WASHER';
                        break;
                    case '3':
                        $name = 'WASHING_DRESS';
                        break;
                    case '4':
                        $name = 'RISING_DRESS';
                        break;
                    case '5':
                        $name = 'EXTRACTION_DRESS';
                        break;
                    case '6':
                        $name = 'WASHING_END';
                        break;
                    case '7':
                        $name = 'WASHER_FREE';
                        break;
                    case '8':
                        $name = 'NULLING_WASHER';
                        break;
                    case '9':
                        $name = 'CONNECT_MCD';
                        break;
                    case '10':
                        $name = 'SUB_BY_WORK';
                        break;
                    case '11':
                        $name = 'MAX_WASHER_EVENT';
                        break;
                } //$event
                break;
            case 'DM':
                switch ($event) {
                    case '-1':
                        $name = 'ZERO_DRYER';
                        break;
                    case '0':
                        $name = 'REFILL_DRYER';
                        break;
                    case '1':
                        $name = 'DRYING';
                        break;
                    case '2':
                        $name = 'DRYING_END';
                        break;
                    case '3':
                        $name = 'DRYER_FREE';
                        break;
                    case '4':
                        $name = 'NULLING_DRYER';
                        break;
                    case '5':
                        $name = 'MAX_DRYER_EVENT';
                        break;
                } //$event
                break;
            case 'CM':
                switch ($event) {
                    case '-5':
                        $name = 'ERR_SENSOR';
                        break;
                    case '-4':
                        $name = 'ERR_PARAM';
                        break;
                    case '-3':
                        $name = 'ERR_NO_FLOW';
                        break;
                    case '-2':
                        $name = 'ZERO_CONDITIONER';
                        break;
                    case '-1':
                        $name = 'ZERO_POWDER';
                        break;
                    case '0':
                        $name = 'NO_POWER_CLEANER';
                        break;
                    case '1':
                        $name = 'POWER_ON_CLEANER';
                        break;
                    case '2':
                        $name = 'REFILL_CLEANER';
                        break;
                    case '3':
                        $name = 'ISSUING_POWDER';
                        break;
                    case '4':
                        $name = 'ISSUING_CONDITIONER';
                        break;
                    case '5':
                        $name = 'NULLING_CLEANER';
                        break;
                    case '6':
                        $name = 'CHANGE_POWDER';
                        break;
                    case '7':
                        $name = 'CHANGE_CONDITIONER';
                        break;
                    case '8':
                        $name = 'CALIBR_POWDER';
                        break;
                    case '9':
                        $name = 'CALIBR_CONDITIONER';
                        break;
                    case '10':
                        $name = 'MAX_CLEANER_EVENT';
                        break;
                } //$event
                break;
            case 'UC':
                switch ($event) {
                    case '0':
                        $name = 'CARDING';
                        break;
                    case '1':
                        $name = 'MAX_UNITCARDS_EVENT';
                        break;
                } //$event
                break;
        } //$type
        return $name;
    }
    //Метод разбора событий автоматов
    function actionEvents() {
        //Записываем логи в файл логгирования
        $murl = $this->request_url();
        file_put_contents('log/data.log', PHP_EOL . date("Y-m-d H:m:i:s"), FILE_APPEND);
        file_put_contents('log/data.log', PHP_EOL . $murl, FILE_APPEND);
        foreach ($_GET as $key => $value) {
            if ($key != 'r') {
                file_put_contents('log/data.log', PHP_EOL . $key . ' : ' . $value, FILE_APPEND);
            } //$key != 'r'
        } //$_GET as $key => $value
        file_put_contents('log/data.log', PHP_EOL . '---------------------------------------------', FILE_APPEND);
        //////////////////////////////////////////////////////////
        
        $murl = $this->request_url();
        $err  = 0;
        //Смотрим пришедшую строку и разбираем её
        $p    = Yii::app()->request->getParam('p', "");
        if ($p == '') {
            $err = 1;
            echo 'Need p!';
            return;
        } //$p == ''
        
        $param = explode('_', $p);
        
        $param1 = explode('*', $param[0]);
        
        
        
        $edate = gmdate("Y-m-d H:i:s", $param1[0]);
        $imei  = $param1[1];
        
        
        $i = 0;
        //////////////////////////////////////////////////////////////
        // Собираем всё в массив и разбираем данные которыее пришли в пакете, а именно события в автомате
        foreach ($param as $element) {
            
            if ($i > 0) {
                
                $param2  = explode('*', $element);
                $date    = date("Y-m-d H:i:s");
                $id_hard = '';
                $type    = $param2[0];
                $event   = '';
                $edate   = '';
                $numDev  = '';
                
                switch ($type) {
                    case 'CB':
                        $event   = $param2[1];
                        $edate   = gmdate("Y-m-d H:i:s", $param1[0] - $param2[2]);
                        $type    = 'CB';
                        $id_hard = $imei . 'CB';
                        break;
                    case 'WM':
                        $numDev  = $param2[1];
                        $event   = $param2[2];
                        $edate   = gmdate("Y-m-d H:i:s", $param1[0] - $param2[3]);
                        $type    = 'WM';
                        $id_hard = $imei . $type . $numDev;
                        break;
                    case 'DM':
                        $numDev  = $param2[1];
                        $event   = $param2[2];
                        $edate   = gmdate("Y-m-d H:i:s", $param1[0] - $param2[3]);
                        $type    = 'DM';
                        $id_hard = $imei . $type . $numDev;
                        break;
                    case 'CM':
                        $numDev  = $param2[1];
                        $event   = $param2[2];
                        $edate   = gmdate("Y-m-d H:i:s", $param1[0] - $param2[3]);
                        $type    = 'CM';
                        $id_hard = $imei . $type . $numDev;
                        break;
                    case 'UC':
                        $numDev  = $param2[1];
                        $event   = $param2[2];
                        $edate   = gmdate("Y-m-d H:i:s", $param1[0] - $param2[3]);
                        $type    = 'UC';
                        $id_hard = $imei . $type . $numDev;
                        break;
                } //$type
                //Если событие не пустое записываем его в базу
                if ($event != '') {
                    $base          = new Base();
                    $base->imei    = $imei;
                    $base->date    = $date;
                    $base->id_hard = $id_hard;
                    $base->type    = $type;
                    $base->event   = $event;
                    $base->edate   = $edate;
                    $base->numDev  = $numDev;
                    if ($base->save()) {
                        $vlog          = new Vlog();
                        $vlog->date    = $edate;
                        $vlog->text    = $this->preText($type, $numDev) . $this->nameEvent($type, $event);
                        $vlog->id_hard = $id_hard;
                        $vlog->imei    = $imei;
                        $vlog->save();
                    } //$base->save()
                } //$event != ''
                
            } //$i > 0
            $i++;
        } //$param as $element
        //Команды
        $coms   = Com::model()->getcom($imei);
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
    //Определения событий по значениям в пакете 
    function preText($type = '', $numDev = '') {
        $text = 'undefined dev';
        switch ($type) {
            case 'CB':
                $text = '[Центральная плата] ';
                break;
            case 'WM':
                $text = '[Стиральная машина ' . $numDev . '] ';
                break;
            case 'DM':
                $text = '[Сушильная машина ' . $numDev . '] ';
                break;
            case 'CM':
                $text = '[Дозатор геля ' . $numDev . '] ';
                break;
            case 'UC':
                $text = '[Диспенсер карт ' . $numDev . '] ';
                break;
        } //$type
        return $text;
    }
    //Разбоор и запись для мониторинга (пакет инициализации)
    function In1($data) {
        //Сначала записываем файлы логов
        $murl = $data;
        
        file_put_contents('log/data.log', PHP_EOL . date("Y-m-d H:m:i:s"), FILE_APPEND);
        file_put_contents('log/data.log', PHP_EOL . $murl, FILE_APPEND);
        foreach ($_GET as $key => $value) {
            if ($key != 'r') {
                file_put_contents('log/data.log', PHP_EOL . $key . ' : ' . $value, FILE_APPEND);
            } //$key != 'r'
        } //$_GET as $key => $value
        file_put_contents('log/data.log', PHP_EOL . '---------------------------------------------', FILE_APPEND);
        ////////////////////////////////////////////////
        
        $murl = $data;
        $err  = 0;
        //Теперь опять разбираем данные в пакете
        
        parse_str(html_entity_decode($murl), $out);
        
        $p = $out['p'];
        if ($p == '') {
            $err = 1;
            echo 'Need p!';
            return;
        } //$p == ''
        
        $param = explode('_', $p);
        
        $param1 = explode('*', $param[0]);
        
        $imei    = $param1[0];
        $fvVer   = $param1[1];
        $kpVer   = $param1[2];
        $srVer   = $param1[3];
        $mTel    = $param1[4];
        $sTel    = $param1[5];
        $ksum    = $param1[6];
        $timeout = $param1[7];
        $date    = date("Y-m-d H:i:s");
        //Записываем инициализационные данные в основную таблицу для вывода в мониторинге и остальных пунктах
        $db      = new Database();
        $sql     = "INSERT INTO base (date, imei, fvVer, kpVer, srVer, mTel, sTel, ksum, timeout)VALUES 
		('$date', '$imei', '$fvVer', '$kpVer', '$srVer', '$mTel', '$sTel', '$ksum', '$timeout')";
        $result  = $db->query($sql);
        
        //Команды для автомата
        $sql  = "SELECT `imei` FROM `com` WHERE `imei` = '$imei'";
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
    //Аналогия предыдущему методу автомат шлет основную информацию но только дополненую тут мы вносим абсолютно все данные
    function Data1($data) {
        //Так же пишем файл логов
        $murl = $data;
        file_put_contents('log/data.log', PHP_EOL . date("Y-m-d H:m:i:s"), FILE_APPEND);
        file_put_contents('log/data.log', PHP_EOL . $murl, FILE_APPEND);
        foreach ($_GET as $key => $value) {
            if ($key != 'r') {
                file_put_contents('log/data.log', PHP_EOL . $key . ' : ' . $value, FILE_APPEND);
            } //$key != 'r'
        } //$_GET as $key => $value
        file_put_contents('log/data.log', PHP_EOL . '---------------------------------------------', FILE_APPEND);
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        
        $murl = $data;
        $err  = 0;
        //Разбор входящих данных в пакете 
        parse_str(html_entity_decode($murl), $out);
        
        $p = $out['p'];
        
        if ($p == '') {
            $err = 1;
            echo 'Need p!';
            return;
        } //$p == ''
        
        $param = explode('_', $p);
        
        $param1 = explode('*', $param[0]);
        
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
            if ($imei != '') {
                $db     = new Database();
                $sql    = "INSERT INTO base 
					(date, imei, gsmSignal, fvVer, numBills, billAcceptorState, id_hard, type, collection, ZigBeeSig, 
					billCash, event, edate, billModem, sumBills, ost, numDev, devSignal, statusDev, colGel)VALUES 
					('$date', '$imei', '$gsmSignal', '$fvVer', '$numBills', '$billAcceptorState', '$id_hard', 
					'$type', '$collection', '$ZigBeeSig', '$billCash', 
					 '$event', '$edate', '$billModem', '$sumBills', '$ost', '$numDev', 
					'$devSignal', '$statusDev', '$colGel')";
                $result = $db->query($sql);
            } //$imei != ''
        } //count($param) < 2
        
        //Повторно разбираем и определяем для событий
        foreach ($param as $element) {
            
            if ($i > 0) {
                
                $param2            = explode('*', $element);
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
    //Метод для разбора в журнал
    function Ev($data) {
        //Записываем файлы логов
        $murl = $data;
        file_put_contents('log/data.log', PHP_EOL . date("Y-m-d H:m:i:s"), FILE_APPEND);
        file_put_contents('log/data.log', PHP_EOL . $murl, FILE_APPEND);
        foreach ($_GET as $key => $value) {
            if ($key != 'r') {
                file_put_contents('log/data.log', PHP_EOL . $key . ' : ' . $value, FILE_APPEND);
            } //$key != 'r'
        } //$_GET as $key => $value
        file_put_contents('log/data.log', PHP_EOL . '---------------------------------------------', FILE_APPEND);
        //////////////////////////////////////////////////////////////////////////////////////////
        
        $murl = $data;
        $err  = 0;
        
        parse_str(html_entity_decode($murl), $out);
        
        $p = $out['p'];
        
        if ($p == '') {
            $err = 1;
            echo 'Need p!';
            return;
        } //$p == ''
        
        
        $edate      = '';
        $imei       = '';
        $type       = '';
        $status_dev = '';
        $ch_uah     = '';
        $ch_map     = '';
        $ch_incasso = '';
        $col_cup    = '';
        $tarif      = '';
        $num_dev    = '';
        $lmodem     = '';
        $price      = '';
        $col_mon    = '';
        $rezim      = '';
        $tstir      = '';
        $otzim_type = '';
        $p_stir     = '';
        $polosk     = '';
        $intensiv   = '';
        $sv         = '';
        $nch        = '';
        $col_gel    = '';
        $by_gel     = '';
        $rdate      = '';
        $esum       = 0;
        
        
        
        $param = explode('_', $p);
        
        $param1 = explode('*', $param[0]);
        
        
        $edate = gmdate("Y-m-d H:i:s", $param1[0]);
        $imei  = $param1[1];
        
        $i = 0;
        //Данные собираем в массив
        foreach ($param as $element) {
            
            if ($i > 0) {
                
                $param2 = explode('*', $element);
                $type   = $param2[0];
                
                //Определяем событие автомата
                switch ($type) {
                    case 'WM':
                        $rdate      = gmdate("Y-m-d H:i:s", $param1[0] - $param2[1]);
                        $num_dev    = $param2[2];
                        $lmodem     = $param2[3];
                        $status_dev = $param2[4];
                        $price      = $param2[5];
                        $col_mon    = $param2[6];
                        $rezim      = $param2[7];
                        $tstir      = $param2[8];
                        $otzim_type = $param2[9];
                        $p_stir     = $param2[10];
                        $polosk     = $param2[11];
                        $intensiv   = $param2[12];
                        break;
                    case 'DM':
                        $rdate      = gmdate("Y-m-d H:i:s", $param1[0] - $param2[1]);
                        $num_dev    = $param2[2];
                        $sv         = $param2[3];
                        $status_dev = $param2[4];
                        $price      = $param2[5];
                        $col_mon    = $param2[6];
                        break;
                    case 'GD':
                        $rdate      = gmdate("Y-m-d H:i:s", $param1[0] - $param2[1]);
                        $status_dev = $param2[2];
                        $price      = $param2[3];
                        $nch        = $param2[4];
                        $col_gel    = $param2[5];
                        $by_gel     = $param2[6];
                        break;
                    case 'DC':
                        $rdate      = gmdate("Y-m-d H:i:s", $param1[0] - $param2[1]);
                        $status_dev = $param2[2];
                        break;
                    case 'CB':
                        $rdate      = gmdate("Y-m-d H:i:s", $param1[0] - $param2[1]);
                        $status_dev = $param2[2];
                        $ch_uah     = $param2[3];
                        $ch_map     = $param2[4];
                        $ch_incasso = $param2[5];
                        $col_cup    = $param2[6];
                        $tarif      = $param2[7];
                        if (isset($param2[8])) {
                            $esum = $param2[8];
                        } //isset($param2[8])
                        break;
                } //$type
                //Записываем в базу данные т.е как и выше подготавливаем модель и заносим инфу в базу
                if ($imei != '') {
                    $db     = new Database();
                    $sql    = "INSERT INTO zlog 
					(edate, imei, 'type', status_dev, ch_uah, ch_map, ch_incasso, col_cup, collection, tarif, 
					num_dev, lmodem, price, col_mon, rezim, tstir, otzim_type, p_stir, polosk, 
					intensiv, sv, nch, col_gel, by_gel, r_date, esum)VALUES 
					('$edate', '$imei', '$type', '$status_dev', '$ch_uah', '$ch_map', '$ch_incasso', 
					'$col_cup', '$collection', '$tarif', '$num_dev', 
					'$lmodem', '$price', '$col_mon', '$rezim', '$tstir', '$otzim_type', '$p_stir', 
					'$polosk', '$intensiv', '$sv', '$nch', '$col_gel', '$by_gel', '$r_date', '$esum', '$doorled')";
                    $result = $db->query($sql);
                    $zlog->save();
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
        echo $return;
    }
    //События автомата полный разбор и информация 
    function Data($data) {
        //В лог файлы для начала
        $murl = $data;
        file_put_contents('log/data.log', PHP_EOL . date("Y-m-d H:m:i:s"), FILE_APPEND);
        file_put_contents('log/data.log', PHP_EOL . $murl, FILE_APPEND);
        foreach ($_GET as $key => $value) {
            if ($key != 'r') {
                file_put_contents('log/data.log', PHP_EOL . $key . ' : ' . $value, FILE_APPEND);
            } //$key != 'r'
        } //$_GET as $key => $value
        file_put_contents('log/data.log', PHP_EOL . '---------------------------------------------', FILE_APPEND);
        ////////////////////////////////////////////////////////////////////////////////////////////
        
        
        $err = 0;
        parse_str(html_entity_decode($murl), $out);
        
        $id_hard = $out['id_hard'];
        
        $imei  = '';
        $type  = '';
        $tvlog = '';
        //Парсим данные и опредиляем их в переменные
        $mas   = array();
        
        if (strpos($id_hard, 'CB') OR strpos($id_hard, 'cb')) {
            $mas   = explode('CB', $id_hard);
            $imei  = $mas[0];
            $type  = 'CB';
            $tvlog = 'CB';
        } //strpos($id_hard, 'CB') OR strpos($id_hard, 'cb')
        if (strpos($id_hard, 'WM') OR strpos($id_hard, 'WM')) {
            $mas   = explode('WM', $id_hard);
            $imei  = $mas[0];
            $type  = 'WM';
            $tvlog = 'Стиральная машина ' . $mas[1];
        } //strpos($id_hard, 'WM') OR strpos($id_hard, 'WM')
        if (strpos($id_hard, 'DM') OR strpos($id_hard, 'DM')) {
            $mas   = explode('DM', $id_hard);
            $imei  = $mas[0];
            $type  = 'DM';
            $tvlog = 'Сушильная машина ' . $mas[1];
        } //strpos($id_hard, 'DM') OR strpos($id_hard, 'DM')
        if (strpos($id_hard, 'GD') OR strpos($id_hard, 'GD')) {
            $mas   = explode('GD', $id_hard);
            $imei  = $mas[0];
            $type  = 'GD';
            $tvlog = 'Дозатор ' . $mas[1];
        } //strpos($id_hard, 'GD') OR strpos($id_hard, 'GD')
        if (strpos($id_hard, 'DC') OR strpos($id_hard, 'DC')) {
            $mas   = explode('DC', $id_hard);
            $imei  = $mas[0];
            $type  = 'DC';
            $tvlog = 'Диспенсор ' . $mas[1];
        } //strpos($id_hard, 'DC') OR strpos($id_hard, 'DC')
        //Условия на проверку данных основных об устройстве
        if ($imei == '') {
            $date   = date("Y-m-d H:i:s");
            $err    = 1;
            $db     = new Database();
            $sql    = "INSERT INTO b_log (date, text, rem)VALUES ('$date', '$murl', 'Required value empty - imei')";
            $result = $db->query($sql);
        } //$imei == ''
        else {
            $sql    = "SELECT `imei` FROM `com` WHERE `id_dev` = '$imei'";
            $device = $db->query($sql);
            if (isset($device->id_dev) AND $device->id_dev != '') {
                
            } //isset($device->id_dev) AND $device->id_dev != ''
            else {
                $date   = date("Y-m-d H:i:s");
                $err    = 1;
                $sql    = "INSERT INTO b_log (date, text, rem)VALUES ('$date', '$murl', 'In database no such imei: '.$imei')";
                $result = $db->query($sql);
            }
        }
        //Если нету ошибок продолжаем
        if ($err != 0) {
            //Записываем в таблицу логгирования {alog}
            $date   = date("Y-m-d H:i:s");
            $sql    = "INSERT INTO a_log (date, text, rem)VALUES ('$date', '$murl', 'IMEI: '.$imei')";
            $result = $db->query($sql);
            
            //Разбираем данные и поредиляем переменные для модели
            $gsmSignal         = $this->my_strip_text(Yii::$app->request->get('gsmSignal', ""));
            $fvVer             = $this->my_strip_text(Yii::$app->request->get('fvVer', ""));
            $WaterMeter        = $this->my_strip_text(Yii::$app->request->get('WaterMeter', ""));
            $ElectricMeter     = $this->my_strip_text(Yii::$app->request->get('ElectricMeter', ""));
            $numBills          = $this->my_strip_text(Yii::$app->request->get('numBills', ""));
            $billAcceptorState = $this->my_strip_text(Yii::$app->request->get('billAcceptorState', ""));
            $collection        = $this->my_strip_text(Yii::$app->request->get('collection', ""));
            $ZigBeeSig         = $this->my_strip_text(Yii::$app->request->get('ZigBeeSig', ""));
            $billCash          = $this->my_strip_text(Yii::$app->request->get('billCash', ""));
            $tariff            = $this->my_strip_text(Yii::$app->request->get('tariff', ""));
            $event             = $this->my_strip_text(Yii::$app->request->get('event', ""));
            $edate             = $this->my_strip_text(Yii::$app->request->get('edate', ""));
            
            if ($edate != '') {
                $edate = str_replace('_', ' ', $edate);
            } //$edate != ''
            //Подготавлваем модельи записываем данные
            $base                    = new Base();
            $base->imei              = $imei;
            $base->type              = $type;
            $base->date              = date("Y-m-d H:i:s");
            $base->gsmSignal         = $gsmSignal;
            $base->fvVer             = $fvVer;
            $base->numBills          = $numBills;
            $base->billAcceptorState = $billAcceptorState;
            $base->id_hard           = $id_hard;
            
            $base->collection = $collection;
            $base->ZigBeeSig  = $ZigBeeSig;
            $base->billCash   = $billCash;
            $base->tariff     = $tariff;
            $base->event      = $event;
            
            if ($edate != '' AND strtotime($edate)) {
                $base->edate = $edate;
            } //$edate != '' AND strtotime($edate)
            
            $base->save();
            $sql    = "INSERT INTO base (imei, type, date, gsmSignal, fvVer, numBills, billAcceptorState, id_hard, collection, ZigBeeSig, billCash, 
			tariff, event, edate)VALUES 
			('$imei', '$type', '$date', '$gsmSignal', '$fvVer', '$numBills', '$billAcceptorState', '$id_hard', '$collection', 
			'$ZigBeeSig', '$billCash', '$tariff', '$event', '$edate')";
            $result = $db->query($sql);
            //Определяем состояние автоматов
            if ($event != '') {
                
                $text = 'Undefined event';
                
                switch ($event) {
                    case '1':
                        $text = 'К работе готова!';
                        break;
                    case '2':
                        $text = 'Питание на машину подано';
                        break;
                    case '3':
                        $text = 'Пополнение счета';
                        break;
                    case '4':
                        $text = 'СТИРКА';
                        break;
                    case '5':
                        $text = 'ПОЛОСКАНИЕ';
                        break;
                    case '6':
                        $text = 'ОТЖИМ';
                        break;
                    case '7':
                        $text = 'СТИРКА ЗАКОНЧЕНА';
                        break;
                    case '9':
                        $text = 'ОБНУЛЕНИЕ';
                        break;
                    case '10':
                        $text = '3Е';
                        break;
                    case '11':
                        $text = 'UE';
                        break;
                    case '12':
                        $text = 'DE';
                        break;
                    case '13':
                        $text = '1E';
                        break;
                    case '14':
                        $text = '4E';
                        break;
                    case '15':
                        $text = 'ЗАВИСАНИЕ С ВОДОЙ';
                        break;
                    case '16':
                        $text = 'НЕТ СВЯЗИ';
                        break;
                    case '18':
                        $text = 'СТИРАЕТ БЕСПЛАТНО';
                        break;
                } //$event
                //Пишем состояние в логги таблицы {vlog}
                $date   = date("Y-m-d H:i:s");
                $sql    = "INSERT INTO vlog (date, text, id_hard, imei)VALUES ('$date', '['.$tvlog.'.] '.$text', $id_hard, '$imei')";
                $result = $db->query($sql);
                
            } //$event != ''
            
            
        } //$err != 0
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
        echo $return;
    }
}