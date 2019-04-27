<?php

namespace frontend\services\logger\src;

use common\models\User;
use DateTime;
use frontend\services\custom\Debugger;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii2tech\ar\softdelete\SoftDeleteBehavior;

/**
 * Class LoggerDto
 * @package frontend\services\logger\src
 */
class LoggerDto implements LoggerDtoInterface
{
    const MINUS_ONE = -1;
    const ZERO = 0;
    const ONE = 1;
    const FORMAT = 'H:i:s Y-m-d';

    public $company_id;
    public $name;
    public $number;
    public $event;
    public $new_state;
    public $old_state;
    public $address;
    public $who_is;
    public $created_at;

    public function createDto($data, $event)
    {
       return $dto = [
            'company_id' => $this->getWhoIs()->company_id,
            'type' => $this->getClassName($data),
            'name' => $this->getName($data),
            'number' => $this->getNumber($data),
            'event' => $event,
            'new_state' => $this->getNewState($data),
            'old_state' => 'old_stat',
            'address' => $this->getAddress($data),
            'who_is' => $this->getWhoIs()->username,
            'created_at' => time(),
            'is_deleted' => self::ZERO,
        ];
    }


    public function getCompanyId()
    {
        return $this->getWhoIs()->company_id;
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
    public function getName($object): string
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

    /**
     * @param $object
     * @return string
     * @throws \yii\db\Exception
     */
    public function getAddress($object)
    {
        if ($this->getClassName($object) == 'User') {
            return $this->address = 'empty';
        }

        if ($this->getClassName($object) == 'UserForm') {
            return $this->address = 'empty';
        }

        if ($this->getClassName($object) == 'UserProfile') {
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

    /**
     * @param $object
     * @return string
     */
    public function getNumber($object)
    {
        if ($this->getClassName($object) == 'User') {
            $this->number = $object->id;
        }

        if ($this->getClassName($object) == 'UserForm') {
            $this->number = $object->id;
        }

        if ($this->getClassName($object) == 'UserProfile') {
            $this->number = $object->user_id;
        }

        if ($this->getClassName($object) == 'WashMachine') {
            $this->number = $object->serial_number . '/' . $object->id . '/' . $object->inventory_number;
        }

        return $this->number;
    }

    /**
     * @param $object
     * @return string
     * @throws \Exception
     */
    public function getEvent($object)
    {
        if (property_exists($object,'created_at')) {
            $cur_time = date(self::FORMAT);
            $currentDate = new DateTime($cur_time);
            $usr_time = date(self::FORMAT, $object->created_at);
            $difference = $currentDate->diff(new DateTime($usr_time))->h;

            if ($difference < self::ONE) {
                $this->event = 'New';
            } else {
                if ($object->is_deleted == self::ONE) {
                    $this->event = 'Delete';
                } else {
                    $this->event = 'Update';
                }
            }

            return $this->event;
        }
        $this->event = 'empty';

        return $this->event;
    }

    /**
     * @param $object
     * @return string
     * @throws \Exception
     */
    public function getOldState($object)
    {
        if ($this->getEvent($object) == 'New') {
            $this->old_state = '---';
        } else {
            echo '<pre>';
            Debugger::d($object->attributes);
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
            ->limit(1)
            ->one();

//        Debugger::dd($this->who_is->company_id);
        return $this->who_is;
    }
}
