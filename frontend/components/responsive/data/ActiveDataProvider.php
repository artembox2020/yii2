<?php

namespace frontend\components\responsive\data;

/**
 * Class ActiveDataProvider
 * @package frontend\components\responsive\data
 */
class ActiveDataProvider extends \yii\data\ActiveDataProvider {

    public $totalCount;

    /**
     * Overrides parent prepareTotalCount() method
     * 
     * @return int
     */
    protected function prepareTotalCount()
    {
        if ($this->totalCount !== null) {

            return $this->totalCount;
        }

        return parent::prepareTotalCount();
    }
}