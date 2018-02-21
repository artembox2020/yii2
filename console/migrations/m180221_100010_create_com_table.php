<?php

use yii\db\Migration;

/**
 * Handles the creation of table `com`.
 */
class m180221_100010_create_com_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('com', [
            'id' => $this->primaryKey(),
            'imei' => $this->string(250),
            'comand' => $this->string(10),
            'status' => $this->string(2),
            'date_sent' => $this->integer(11)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('com');
    }
}
