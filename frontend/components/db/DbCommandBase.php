<?php

namespace frontend\components\db;
use Yii;
use yii\base\Component;

/**
 * Class DbCommandBase
 * @package frontend\components\db
 */
class DbCommandBase extends Component
{
    public $queryString;
    public $bindValues;

    const ZERO = null;

    /**
     * Sets query string and binded values
     */
    public function setFields($queryString = self::ZERO, $bindValues = self::ZERO)
    {
        $this->queryString = $queryString ?? '';
        $this->bindValues = $bindValues ?? [];
    }
}