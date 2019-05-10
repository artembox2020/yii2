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
    const ZERO = 0;

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
     * Makes existing unit query by timestamps
     * 
     * @param int $start
     * @param int $end
     * @param ActiveRecord $inst
     * @param ActiveRecord $bInst
     * @param string $fieldInst
     * @param string $select
     */
    public function getExistingUnitQueryByTimestamps($start, $end, $inst, $bInst, $fieldInst, $select)
    {
        $tableName = $inst::tableName();

        $queryString = "SELECT $select FROM $tableName WHERE ";
        $queryString .= "$fieldInst = :fieldInst AND created_at < :end ";
        $queryString .= " AND (is_deleted = false OR (is_deleted = true AND deleted_at > :start))";

        $bindValues = [':fieldInst' => $bInst->id, ':start' => $start, ':end' => $end];
        $this->setFields($queryString, $bindValues);
    }

    /**
     * Gets last item query from `j_temp`table 
     * 
     * @param string $type
     * @param string $param_type
     * @param int $entity_id
     * 
     * @return \yii\db\Command
     */
    public function getUnitLastTempItemQuery($type, $param_type, $entity_id)
    {
        $queryString = "SELECT id, start, end, value, other FROM j_temp WHERE type = :type AND param_type = :param_type AND entity_id = :entity_id";
        $queryString .= " ORDER BY end DESC LIMIT 1";
        $bindValues = [':type' => $type, ':param_type' => $param_type, ':entity_id' => $entity_id];

        $command = Yii::$app->db->createCommand($queryString)->bindValues($bindValues);

        return $command;
    }

    /**
     * Gets last item from `j_temp` table 
     * 
     * @param string $type
     * @param string $param_type
     * @param int $entity_id
     * 
     * @return array
     */
    public function getUnitLastTempItem($type, $param_type, $entity_id)
    {
        $command  = $this->getUnitLastTempItemQuery($type, $param_type, $entity_id);
        $item = $command->queryOne();

        if (empty($item)) {

            return false;
        }

        return $item;
    }

    /**
     * Inserts/Updates `j_temp` table 
     * 
     * @param array $item
     */
    public function upsertUnitTempItem(array $item)
    {
        Yii::$app->db->createCommand()->upsert('j_temp', $item, true)->execute();
    }

    /**
     * Deletes item query from `j_temp` table 
     * 
     * @param string $type
     * @param string $param_type
     * @param int $entity_id
     */
    public function deleteUnitTempByEntityIdQuery($type, $param_type, $entity_id)
    {
        return Yii::$app->db->createCommand()->delete(
            'j_temp',
            [
                'type' => $type,
                'param_type' => $param_type,
                'entity_id' =>$entity_id
            ]
        );
    }

    /**
     * Deletes item from `j_temp` table 
     * 
     * @param string $type
     * @param string $param_type
     * @param int $entity_id
     */
    public function deleteUnitTempByEntityId($type, $param_type, $entity_id)
    {
        $command = $this->deleteUnitTempByEntityIdQuery($type, $param_type, $entity_id);

        return $command->execute();
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