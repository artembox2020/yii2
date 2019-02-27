<?php

namespace frontend\services\globals;

/**
 * Class QueryOptimizer
 * @package frontend\services\globals;
 */
class QueryOptimizer implements QueryOptimizerInterface
{
    const MEMORY_USAGE_LIMIT = 80 * 1024 * 1024;

    /**
     * Fetches one item by given query
     * Query is identified by its hash and item is put into global array 
     * whose key is a query hash
     * 
     * @param \yii\db\query $query
     * @return Model|null
     */
    public static function getItemByQuery($query)
    {
        global $globalItemsByQuery;
        self::initGlobalItemsByQuery();
        $hash = self::getHashByQuery($query);

        if (array_key_exists($hash, $globalItemsByQuery)) {

            return $globalItemsByQuery[$hash];
        }

        if (!self::checkMemoryUsageExceeding()) {

            return $query->one();
        }

        $globalItemsByQuery[$hash] = $query->one();

        return $globalItemsByQuery[$hash];
    }

    /**
     * Fetches all items by given query
     * Query is identified by its hash and items are put into global array 
     * whose key is a query hash
     * 
     * @param \yii\db\query $query
     * @return array
     */
    public static function getItemsByQuery($query)
    {
        global $globalItemsByQuery;
        self::initGlobalItemsByQuery();
        $hash = self::getHashByQuery($query);

        if (array_key_exists($hash, $globalItemsByQuery)) {

            return $globalItemsByQuery[$hash];
        }

        if (!self::checkMemoryUsageExceeding()) {

            return $query->all();
        }

        $globalItemsByQuery[$hash] = $query->all();

        return $globalItemsByQuery[$hash];
    }

    /**
     * Gets the number of items by given query
     * Query is identified by its hash and items are put into global array 
     * whose key is a query hash
     * 
     * @param \yii\db\query $query
     * @return int
     */
    public static function getItemsCountByQuery($query)
    {
        global $globalItemsByQuery;
        self::initGlobalItemsByQuery();
        $hash = self::getHashByQuery($query);

        if (array_key_exists($hash, $globalItemsByQuery)) {

            return count($globalItemsByQuery[$hash]);
        }

        if (!self::checkMemoryUsageExceeding()) {

            return count($query->all());
        }

        $globalItemsByQuery[$hash] = $query->all();

        return count($globalItemsByQuery[$hash]);
    }

    /**
     * Gets sha1 hash by given query
     * 
     * @param \yii\db\query $query
     * @return string
     */
    public static function getHashByQuery($query)
    {

        return sha1(trim($query->createCommand()->rawSql));
    }

    /**
     * Initializes global variable if necessary
     */
    public static function initGlobalItemsByQuery()
    {
        global $globalItemsByQuery;

        if (empty($globalItemsByQuery)) {
            $globalItemsByQuery = [];
        }
    }

    /**
     * Checks memory usage for exceeding
     */
    public static function checkMemoryUsageExceeding()
    {

        return memory_get_usage() > self::MEMORY_USAGE_LIMIT ? false : true;
    }
}