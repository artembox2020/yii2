<?php

namespace frontend\components\db;
use Yii;
use yii\base\Component;

/**
 * Class DbCommandHelper
 * @package frontend\components\db
 */
class DbCommandHelper extends DbCommandBase
{
    /**
     * Makes base unit query
     * 
     * @param int $start
     * @param int $end
     * @param ActiveRecord $inst
     * @param ActiveRecord $bInst
     * @param string $fieldInst
     * @param string $select
     */
    public function getBaseUnitQueryByTimestamps($start, $end, $inst, $bInst, $fieldInst, $select)
    {
        $tableName = $inst::tableName();

        $queryString = "SELECT $select FROM $tableName WHERE ";
        $queryString .= "$fieldInst = :fieldInst AND created_at >= :start AND created_at <= :end";

        $bindValues = [':fieldInst' => $bInst->id, ':start' => $start, ':end' => $end];
        $this->setFields($queryString, $bindValues);
    }

    /**
     * Gets items count, based on query string
     * 
     * @return int
     */
    public function getCount()
    {
        $queryString = $this->queryString;
        $queryString = preg_replace("/SELECT\s.*\sFROM\s/", "SELECT COUNT(*) FROM ", $queryString);
        $command = Yii::$app->db->createCommand($queryString)->bindValues($this->bindValues);

        return $command->queryScalar();
    }

    /**
     * Adds query string to query
     * 
     * @param string $queryString
     */
    public function addQueryString($queryString)
    {
        $this->queryString .= " ".$queryString;
    }

    /**
     * Gets all items, based on query string 
     * 
     * @return array
     */
    public function getItems()
    {
        $command = Yii::$app->db->createCommand($this->queryString)->bindValues($this->bindValues);

        return $command->queryAll();
    }

    /**
     * Gets one item, based on query string 
     * 
     * @return array
     */
    public function getItem()
    {
        $command = Yii::$app->db->createCommand($this->queryString)->bindValues($this->bindValues);

        return $command->queryOne();
    }

    /**
     * Gets scalar value, based on query string 
     * 
     * @return mixed
     */
    public function getScalar()
    {
        $command = Yii::$app->db->createCommand($this->queryString)->bindValues($this->bindValues);

        return $command->queryScalar();
    }

    /**
     * Gets one column items, based on query string 
     * 
     * @return array
     */
    public function getColumn()
    {
        $command = Yii::$app->db->createCommand($this->queryString)->bindValues($this->bindValues);

        return $command->queryColumn();
    }
}