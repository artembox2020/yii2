<?php

use yii\db\Migration;

/**
 * Handles the creation of table `a_log`.
 */
class m180221_095020_create_a_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('a_log', [
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
        $this->dropTable('a_log');
    }
}
