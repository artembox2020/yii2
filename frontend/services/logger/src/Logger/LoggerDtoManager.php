<?php

namespace frontend\services\logger\src\Logger;

use frontend\models\AddressBalanceHolder;
use frontend\services\custom\Debugger;
use Yii;

/**
 * Class LoggerDtoManager
 * @package frontend\services\logger\src\Logger
 */
class LoggerDtoManager implements LoggerDtoManagerInterface
{
    const MINUS_ONE = -1;
    public $company_id;
    public $name;
    public $type;
    public $event_row = [];
    public $new_sate;
    public $address;

    /**
     * LoggerDtoManager constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->company_id = $data->company_id;
        $this->type = $this->getClassName($data);
        $this->event_row = ['created', $data->username];
        $this->new_sate = 'new';
        $this->address = $this->getAddress($data->company_id);

        if (array_key_exists('company_id', $data)) {
            $this->company_id = (string)$data['company_id'];
        }

        if (array_key_exists('username', $data)) {
            $this->name = (string)$data['username'];
        }
    }

    /**
     * @param $object
     * @return string Class Name
     */
    public function getClassName($object)
    {
        $nameSpace = get_class($object);
        return join('', array_slice(explode('\\', $nameSpace), self::MINUS_ONE));
    }

    public function getAddress($company_id)
    {
        $params = [':id' => $company_id, ':is_deleted' => false];

        $address = Yii::$app->db->createCommand('
                        SELECT * 
                        FROM address_balance_holder 
                        WHERE company_id=:id 
                          AND is_deleted=:is_deleted', $params)
            ->queryOne();

        Debugger::d($company_id);
        Debugger::dd($address);
        return $address['address'];
    }
}
