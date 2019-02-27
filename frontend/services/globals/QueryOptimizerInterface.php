<?php

namespace frontend\services\globals;

/**
 * Interface QueryOptimizerInterface
 * @package frontend\services\globals
 */
interface QueryOptimizerInterface
{
    /**
     * Fetches one item by given query
     * Query is identified by its hash and item is put into global array 
     * whose key is a query hash
     * 
     * @param \yii\db\query $query
     * @return Model|null
     */
    public static function getItemByQuery($query);

    /**
     * Fetches all items by given query
     * Query is identified by its hash and items are put into global array 
     * whose key is a query hash
     * 
     * @param \yii\db\query $query
     * @return array
     */
    public static function getItemsByQuery($query);

    /**
     * Gets sha1 hash by given query
     * 
     * @param \yii\db\query $query
     * @return string
     */
    public static function getHashByQuery($query);

    /**
     * Checks memory usage for exceeding
     */
    public static function checkMemoryUsageExceeding();
}
