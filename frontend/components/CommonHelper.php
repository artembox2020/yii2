<?php

namespace frontend\components;

use Yii;
use yii\base\Component;
use yii\helpers\Url;
use yii\helpers\Html;
use frontend\models\AddressBalanceHolder;
use frontend\models\BalanceHolder;
use frontend\models\Imei;
use frontend\models\WmMashine;
use frontend\services\globals\Entity;

/**
 * Commom purposes helper: links by objects, links by object types
 * Class CommonHelper
 * @package frontend\components
 */
class CommonHelper extends Component {

    const OBJECT_TYPE_ADDRESS = 'AddressBalanceHolder';
    const OBJECT_TYPE_BALANCE_HOLDER = 'BalanceHolder';
    const OBJECT_TYPE_IMEI = 'Imei';
    const OBJECT_TYPE_WM = 'WmMashine';

    const OBJECT_TYPES = [
        self::OBJECT_TYPE_ADDRESS, self::OBJECT_TYPE_BALANCE_HOLDER,
        self::OBJECT_TYPE_IMEI, self::OBJECT_TYPE_WM
    ];

    const DEFAULT_URL = '#';
    const MINUS_ONE = -1;

    const MODEL_NAMESPACE = "\\frontend\\models\\";

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
     * Gets object view page link by object
     * 
     * @param Object $object
     * @param array $options
     * @params string|bool $caption
     * 
     * @return string
     */
    public function link($object, $options = [], $caption = false)
    {
        $caption = !empty($caption) ? $caption : $this->getObjectTitle($object);

        if ($options == []) {
            $options['class'] = 'new-tab';
        }

        $options['class'] .= ' lnk-cmn-helper';

        return Html::a($caption, $this->getObjectViewUrl($object), $options);
    }

    /**
     * Gets object view page link by type and name
     * 
     * @param string $type
     * @param string $name
     *
     * @return string
     */
    public function linkByType($type, $name) {

        if (!in_array($type, self::OBJECT_TYPES) || empty($name)) {

            return $name;
        }

        $entity = new Entity();

        $object = self::MODEL_NAMESPACE.$type;

        $object = $object::find()
            ->andWhere(['like', $this->getObjectTitleField(null, $type), $name])
            ->andWhere(['company_id' => $entity->getCompanyId()])
            ->limit(1)
            ->one();

        return $this->link($object);
    }

    /**
     * Gets object view page url
     * 
     * @param Object $object
     *
     * @return string
     */
    public function getObjectViewUrl($object)
    {

        if (empty($object)) {

            return self::DEFAULT_URL;
        }

        $controller = '';
        switch($this->getClassName($object)) {
            case self::OBJECT_TYPE_ADDRESS:
                $controller = 'address-balance-holder';
                break;
            case self::OBJECT_TYPE_BALANCE_HOLDER:
                $controller = 'balance-holder';
                break;
            case self::OBJECT_TYPE_IMEI:
                $controller = 'imei';
                break;
            case self::OBJECT_TYPE_WM:
                $controller = 'wm-mashine';
                break;
        }

        return Url::to(['/'.$controller.'/view', 'id' => $object->id]);
    }

    /**
     * Gets object default title field
     *
     * @param Object $object
     * @param string|bool $type
     *
     * @return string|null
     */
    public function getObjectTitleField($object, $type = false)
    {
        if (empty($object) && empty($type)) {

            return null;
        }

        $title = null;
        $type = empty($type) ? $this->getClassName($object) : $type;

        switch($type) {
            case self::OBJECT_TYPE_ADDRESS:
                $title = 'address';
                break;
            case self::OBJECT_TYPE_BALANCE_HOLDER:
                $title = 'name';
                break;
            case self::OBJECT_TYPE_IMEI:
                $title = 'imei';
                break;
            case self::OBJECT_TYPE_WM:
                $title = 'model';
                break;
        }

        return $title;
    }

    /**
     * Gets object default title
     *
     * @param Object $object
     * @param string|bool $type
     *
     * @return string|null
     */
    public function getObjectTitle($object, $type = false)
    {
        $field = $this->getObjectTitleField($object, $type);

        if (empty($field)) {

            return null;
        }

        return $object->$field;
    }
}