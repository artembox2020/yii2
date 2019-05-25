<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%logger}}`.
 */
class m190323_065530_create_logger_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%logger}}', [
            'id' => $this->primaryKey(),
            'company_id' => $this->integer(),
            'type' => $this->string(),
            'name' => $this->string(),
            'number' => $this->string(),
            'event' => $this->string(),
            'new_state' => $this->string(),
            'old_state' => $this->string(),
            'address' => $this->string(),
            'who_is' => $this->string(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'deleted_at' => $this->integer(),
            'is_deleted' => $this->boolean()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%logger}}');
    }
}
