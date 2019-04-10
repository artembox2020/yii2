<?php

namespace frontend\services\logger\src;

use common\models\User;
use DateTime;
use frontend\services\custom\Debugger;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii2tech\ar\softdelete\SoftDeleteBehavior;

class LoggerDto implements LoggerDtoInterface
{
    const MINUS_ONE = -1;
    const ZERO = 0;
    const ONE = 1;
    const FORMAT = 'Y-m-d H:i:s';

    public $name;
    public $number;
    public $event;
    public $new_state;
    public $old_state;
    public $address;
    public $who_is;
    public $created_at;

    public function createDto($data)
    {
       return $dto = [
            'company_id' => $data->company_id,
            'type' => $this->getClassName($data),
            'name' => $this->getName($data),
            'number' => $this->getNumber($data),
            'event' => $this->getEvent($data),
            'new_state' => $this->getNewState($data),
            'old_state' => $this->getOldState($data),
            'address' => $this->getAddress($data),
            'who_is' => $this->getWhoIs(),
            'created_at' => time(),
            'is_deleted' => self::ZERO,
        ];
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

    /**
     * @param $object
     * @return mixed
     */
    public function getName($object)
    {
        if (isset($object->username)) {
            $this->name = $object->username;
        }

        if (isset($object->name)) {
            $this->name = $object->name;
        }

        if (isset($object->imei)) {
            $this->name = $object->imei;
        }

        if (isset($object->number_device)) {
            $this->name = $object->number_device;
        }


        return $this->name;
    }

    public function getAddress($object)
    {
        if ($this->getClassName($object) == 'User') {
            return $this->address = 'empty';
        }

        $params = [':id' => $object->id, ':is_deleted' => false];

        $address = Yii::$app->db->createCommand('
                        SELECT * 
                        FROM address_balance_holder 
                        WHERE company_id=:id 
                          AND is_deleted=:is_deleted', $params)
            ->queryOne();

        return $address['address'];
    }

    public function getNumber($object)
    {
        if ($this->getClassName($object) == 'User') {
            $this->number = $object->id;
        }

        if ($this->getClassName($object) == 'WashMachine') {
            $this->number = $object->serial_number . '/' . $object->id . '/' . $object->inventory_number;
        }

        return $this->number;
    }

    public function getEvent($object)
    {
        $cur_time = date(self::FORMAT);
        $currentDate = new DateTime($cur_time);
        $usr_time = date(self::FORMAT, $object->created_at);
        $difference = $currentDate->diff(new DateTime($usr_time))->h;

        if ($difference < self::ONE) {
            $this->event = 'New';
        } else {
            $this->event = 'Old';
        }

        return $this->event;
    }

    public function getOldState($object)
    {
        if ($this->getEvent($object) == 'New') {
            $this->old_state = '---';
        } else {
            $this->old_state = 'getOldState';
        }
        return $this->old_state;
    }

    public function getNewState($object)
    {
        $this->new_state = 'new';
        return $this->new_state;
    }

    public function getWhoIs()
    {
        $this->who_is = User::find()
            ->andWhere(['id' => Yii::$app->user->id])
            ->one();
        return $this->who_is->username;
    }
}
