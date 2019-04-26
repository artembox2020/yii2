<?php

namespace frontend\components\db;
use Yii;
use yii\base\Component;

/**
 * Class DbCommandHelperOptimizer
 * @package frontend\components\db
 */
class DbCommandHelperOptimizer extends DbCommandHelper
{
    const MEMORY_USAGE_LIMIT = 100 * 1024 * 1024;

    /**
     * Checks memory usage for exceeding
     */
    public static function checkMemoryUsageExceeding()
    {

        return memory_get_usage() > self::MEMORY_USAGE_LIMIT ? false : true;
    }

    /**
     * Gets sha1 hash by given query
     * 
     * @param \yii\db\Command $query
     * @return string
     */
    public function getHashByQuery($query)
    {

        return sha1(trim($query->rawSql));
    }

    /**
     * Gets data by hash
     * 
     * @param string $hash
     * 
     * @return array|bool
     */
    public function getDataByHash($hash)
    {
        global $globalQueryItems;

        if (empty($globalQueryItems)) {
            $globalQueryItems = [];
        }

        if (array_key_exists($hash, $globalQueryItems)) {

            return $globalQueryItems[$hash];
        }

        return false;
    }

    /**
     * Puts hash by given query
     * 
     * @param string $hash
     * @param \yii\db\Command $query
     * @param string $expr
     * 
     * @return array|int
     */
    public function putDataByHash($hash, $query, $expr)
    {
        global $globalQueryItems;

        if (!$this->checkMemoryUsageExceeding()) {

            return $query->$expr();
        }

        $globalQueryItems[$hash] = $query->$expr();

        return $globalQueryItems[$hash];
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
        global $globalQueryItems;

        $query = $this->getUnitLastTempItemQuery($type, $param_type, $entity_id);
        $hash = $this->getHashByQuery($query);
        $item = $this->getDataByHash($hash);

        if ($item) {

            return $item;
        }

        return $this->putDataByHash($hash, $query, 'queryOne');
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
        $query = $this->deleteUnitTempByEntityIdQuery($type, $param_type, $entity_id);
        $hash = $this->getHashByQuery($query);

        if ($this->getDataByHash($hash)) {

            return false;
        }

        return $this->putDataByHash($hash, $query, 'execute');
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

        $hash = $this->getHashByQuery($command);
        $item = $this->getDataByHash($hash);

        if ($item) {

            return $item;
        }

        return $this->putDataByHash($hash, $command, 'queryScalar');
    }
}