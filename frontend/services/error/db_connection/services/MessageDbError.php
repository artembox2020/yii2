<?php

namespace frontend\services\error\db_connection\services;

use frontend\services\custom\Debugger;
use yii\helpers\Json;

/**
 * Class MessageDbError
 * @package frontend\services\error\db_connection\services
 */
class MessageDbError
{
//    public $to = 'dmytro.v.kovtun@gmail.com';
//    public $sashabardash = 'sashabardash@gmail.com';
//    public $info = 'info@postirayka.com.ua';
    public $monstrpro = 'monstrpro@gmail.com';

    public function actionRun($message)
    {
        $date = date('d,m,Y h:i:s');

        $this->save($date);
        echo $message;
//        Debugger::dd();
//        if ($interval > $result) {
//            echo $message;
//        }
    }

    public function save($data)
    {
        $file = file_get_contents('frontend/services/error/db_connection/file/data.json');  // Открыть файл data.json
        $taskList = Json::decode($data);        // Декодировать в массив
        echo'adsf';
//        Debugger::dd($taskList);
        unset($file);                               // Очистить переменную $file
        $taskList[] = array('name'=>$data);        // Представить новую переменную как элемент массива, в формате 'ключ'=>'имя переменной'
        file_put_contents('frontend/services/error/db_connection/file/data.json', Json::encode($taskList));  // Перекодировать в формат и записать в файл.

        unset($taskList);
    }
}
