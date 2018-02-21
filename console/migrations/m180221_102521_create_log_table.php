<?php

use yii\db\Migration;

/**
 * Handles the creation of table `log`.
 */
class m180221_102521_create_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('log', [
            'id' => $this->bigInteger(20)->notNull(),
            'level' => $this->integer(11),
            'category' => $this->string(255),
            'log_time' => $this->double(),
            'prefix' => $this->text(),
            'message' => $this->text()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('log');
    }
}
