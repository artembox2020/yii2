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

    private $_event;

    public function createDto($data, $event)
    {
        $this->_event = $event;

        switch ($event) {
            case $this->getClassName($data) == 'BalanceHolder':
                return $this->getBalanceHolderDto($data, $event);
                break;
            case $this->getClassName($data) == 'WmMashine':
                return null;
                break;
            case $this->getClassName($data) == 'User':
                return null;
                break;     
            default:

                return null;
        }
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
        if ($this->getClassName($object) == 'BalanceHolder') {
            return $this->getOldStateBalanceHolder($object);
//            $long = strtotime($object->date_start_cooperation);
//            $object->setAttribute('date_start_cooperation', $long);
//            $long = strtotime($object->date_connection_monitoring);
//            $object->setAttribute('date_connection_monitoring', $long);
//            $newAttributes = $object->getDirtyAttributes();
//            $oldAttributes = $object->getOldAttributes();
//            Debugger::d($oldAttributes['date_start_cooperation']);
//            Debugger::dd($newAttributes);
        }

        if ($this->_event == 'Update' && $object->getDirtyAttributes()) {
        $newAttributes = $object->getDirtyAttributes();
        $oldAttributes = $object->getOldAttributes();

        foreach ($newAttributes as $key => $value) {
            if (array_key_exists($key, $newAttributes)) {
                $array[] = $oldAttributes[$key];
            }
        }

        $comma_separated = implode('<br />', $array);
        $this->old_state = $comma_separated;
        } else {
            $this->old_state = '---';
        }

//        Debugger::d($newAttributes);
//        Debugger::d($oldAttributes);
//        Debugger::dd($this->old_state);

        return $this->old_state;
    }

    public function getNewState($object)
    {

////        $object->offsetUnset('date_start_cooperation');
////        $object->offsetUnset('date_connection_monitoring');
//        $oldAttributes = $object->getOldAttributes();
//        $newAttributes = $object->getDirtyAttributes();
//            Debugger::d($newAttributes);
//            Debugger::dd($oldAttributes);

        if ($this->_event == 'Update' && $object->getDirtyAttributes()) {
            $newAttributes = $object->getDirtyAttributes();
            foreach ($newAttributes as $key => $value) {
                if (array_key_exists($key, $newAttributes)) {
                    $array[] = $newAttributes[$key];
                }
            }

            $comma_separated = implode('<br />', $array);
            $this->new_state = $comma_separated;
        } else {
            $this->new_state = 'new';
        }

        return $this->new_state;
    }

    public function getWhoIs()
    {
        $this->who_is = User::find()
            ->andWhere(['id' => Yii::$app->user->id])
            ->limit(1)
            ->one();

        return $this->who_is;
    }

    public function getBalanceHolderDto($data, $event)
    {
        $old = $this->getOldStateBalanceHolder($data);
        $new = $this->getNewState($data);

//        Debugger::d($old );
//        Debugger::dd($new);

        if (isset($old) or isset($new)) {
            return $dto = [
                'company_id' => $this->getWhoIs()->company_id,
                'type' => $this->getClassName($data),
                'name' => $this->getName($data),
                'number' => $this->getNumber($data),
                'event' => $event,
                'new_state' => $this->getNewState($data),
                'old_state' => $this->getOldState($data),
                'address' => $this->getAddress($data),
                'who_is' => $this->getWhoIs()->username,
                'created_at' => time(),
                'is_deleted' => self::ZERO,
            ];
        } else {
            return null;
        }
    }

    public function getOldStateBalanceHolder($object)
    {
        $array = [];
        if ($this->_event == 'Create') {

            return $this->old_state = 'empty';
        }
//        $oldAttributes = $object->getOldAttributes();
//        $newAttributes = $object->getDirtyAttributes();
//////            Debugger::d($a);
//        Debugger::d($newAttributes);
//        Debugger::dd($oldAttributes);
        if (empty($object->getDirtyAttributes())) {
            $this->old_state = 'nothing update';
            return $this->old_state;
        }

        if ($this->_event == 'Update') {

            if (empty($object->date_start_cooperation)) {
                $object->setAttribute('date_start_cooperation', null);
            }

            if (isset($object->date_start_cooperation)) {
                $long = strtotime($object->date_start_cooperation);
                if ($long == $object->getOldAttribute('date_start_cooperation')) {
                    $object->offsetUnset('date_start_cooperation');
                }
            }

            if (empty($object->date_connection_monitoring)) {
                $object->setAttribute('date_connection_monitoring', null);
            }

            if (isset($object->date_connection_monitoring)) {
                $long = strtotime($object->date_connection_monitoring);
                if ($long == $object->getOldAttribute('date_connection_monitoring')) {
                    $object->offsetUnset('date_connection_monitoring');
                }
            }

//            if (empty($object->getDirtyAttributes())) {
//                $this->old_state = 'nothing update';
//                return $this->old_state;
//            }


//            $a = $object->getDirtyAttributes();
//            $b = $object->getOldAttributes();
//            Debugger::d($a);
//            Debugger::dd($b);

            if ($object->getDirtyAttributes()) {
                $oldAttributes = $object->getOldAttributes();
                $newAttributes = $object->getDirtyAttributes();
                foreach ($oldAttributes as $key => $value) {
                    if (array_key_exists($key, $newAttributes)) {
                        if ($key == 'date_start_cooperation')
                        $array[] = $oldAttributes[$key];
                    }
                }
            }

            $comma_separated = implode('<br />', $array);
            $this->old_state = $comma_separated;

            return $this->old_state;
        }
    }
}
