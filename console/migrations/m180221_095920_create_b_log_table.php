<?php

use yii\db\Migration;

/**
 * Handles the creation of table `b_log`.
 */
class m180221_095920_create_b_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('b_log', [
            'id' => $this->primaryKey(),
            'date' => $this->dateTime(),
            'text' => $this->binary(),
            'rem' => $this->string(250)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('b_log');
    }
}
