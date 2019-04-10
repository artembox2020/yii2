<?php

namespace frontend\services\logger\src\storage;

use yii\db\Connection;

/**
 * Class LoggerDaoStorage
 * @package frontend\services\logger\src\storage
 */
class LoggerDaoStorage implements StorageInterface
{
    /** @var string  */
    const  TABLE_NAME = 'logger';

    /** @var Connection  */
    private $connection;

    /**
     * LoggerDaoStorage constructor.
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return array
     * @throws \yii\db\Exception
     */
    public function load()
    {
        return $this->connection->createCommand('SELECT * FROM logger order by created_at desc')
            ->queryAll();
    }

    /**
     * @param array $array
     * @throws \yii\db\Exception
     */
    public function save(array $array): void
    {
        $this->connection->createCommand()
            ->insert('logger', $array)
            ->execute();
    }

    /**
     * @param $id
     * @throws \yii\db\Exception
     */
    public function delete($id): void
    {
        $this->connection->createCommand()
            ->delete(self::TABLE_NAME, "id = $id")
            ->execute();
    }
}
