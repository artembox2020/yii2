<?php

use yii\db\Migration;

/**
 * Class m191008_141503_t_bot_monitor
 */
class m191008_141503_t_bot_monitor extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('t_bot_monitor', [
            'id' => $this->primaryKey(),
            'address' => $this->string(100),
            'num_w' => $this->integer(),
            'status_w' => $this->integer(),
            'time' => $this->integer(),
            'chat_id' => $this->string(),
            'key' => $this->string(),
            'is_active' => $this->boolean(),
            'created_at' => $this->integer()
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191008_141503_t_bot_monitor cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191008_141503_t_bot_monitor cannot be reverted.\n";

        return false;
    }
    */
}
