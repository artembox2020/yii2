<?php

use yii\db\Migration;

/**
 * Class m191008_152723_change_type_field_t_bot_monitor_Date
 */
class m191008_152723_change_type_field_t_bot_monitor_Date extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('t_bot_monitor', 'created_at', 'datetime');
        $this->alterColumn('t_bot_monitor', 'time', 'string');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191008_152723_change_type_field_t_bot_monitor_Date cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191008_152723_change_type_field_t_bot_monitor_Date cannot be reverted.\n";

        return false;
    }
    */
}
