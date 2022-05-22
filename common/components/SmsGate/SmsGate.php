<?php

namespace common\components\SmsGate;

use common\models\SmsGateItem;

class SmsGate
{
    public const CYRILLIC_SINGLE_SMS_SIZE = 70;
    public const LATIN_SINGLE_SMS_SIZE = 160;

    public $pdo;

    public function __construct()
    {
        //$this->init();
    }

    public function test()
    {
        echo env('DB_DSN');
    }

    public function init()
    {
        $this->pdo = new \PDO(env('DB_DSN'), env('DB_USERNAME'), env('DB_PASSWORD'));
        //$this->pdo->setAttribute(PDO_ATTRIBUTE_DEFAULT_FETCH_MODE, PDO_FETCH_ASSOC);
        /*$sql_query = "REVOKE ALL PRIVILEGES ON *.* FROM `" . $login . "`;";

$sql_query .= "GRANT ALL PRIVILEGES ON *.* TO `" . $login . "` REQUIRE NONE WITH GRANT OPTION MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;";

$sql_query .= "GRANT SELECT, INSERT, UPDATE, DELETE ON `" . $dbname . "`.* TO `" . $login . "`; FLUSH PRIVILEGES;";
        $sql_query .= "GRANT FILE ON * . * TO '" .login. "'@'localhost' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;";
        $pdo->query($sql_query);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $dbname = "`".str_replace("`","``",$dbname)."`";
        $pdo->query("CREATE DATABASE IF NOT EXISTS $dbname");
        $pdo->query("use $dbname");
        */
        //print_r($this->pdo);
        $query = <<<DOC
    CREATE TABLE IF NOT EXISTS gate ( id INT NOT NULL AUTO_INCREMENT , service_id INT NOT NULL , connect_params JSON NOT NULL , created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, balance INT NOT NULL , other TEXT NULL , PRIMARY KEY (id)) ENGINE = InnoDB;
DOC;

        $this->pdo->query($query);
    }

    public function getSmsSize($text)
    {
        preg_match_all( '/[а-яё€\–№`]/ui', $text, $matches);
        $cyrillicChars = count($matches[0]);

        return ceil(mb_strlen($text)/($cyrillicChars > 0 ? self::CYRILLIC_SINGLE_SMS_SIZE : self::LATIN_SINGLE_SMS_SIZE));
    }

    public function send($to, $text)
    {
        $smsSize = $this->getSmsSize($text);
        $gateItem = \common\models\SmsGateItem::find()
            ->innerJoin('sms_gate', 'sms_gate_item.sms_gate_id = sms_gate.id')
            ->andWhere(['is', 'sms_gate_item.deleted_at', new \yii\db\Expression('null')])
            ->andWhere('sms_gate_item.balance/sms_gate.sms_price >= ' . $smsSize)
            ->orderBy(['sms_gate_item.balance' => SORT_ASC])
            ->limit(1)
            ->one()
        ;

        if ($gateItem) {

            return $gateItem->send($to, $text);
        }
    }
}