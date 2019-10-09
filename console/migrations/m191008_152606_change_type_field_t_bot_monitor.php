<?php

use yii\db\Migration;

/**
 * Class m191008_152606_change_type_field_t_bot_monitor
 */
class m191008_152606_change_type_field_t_bot_monitor extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('t_bot_monitor', 'created_at', 'datetime');
        $this->alterColumn('t_bot_monitor', 'time', 'datetime');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191008_152606_change_type_field_t_bot_monitor cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191008_152606_change_type_field_t_bot_monitor cannot be reverted.\n";

        return false;
    }
    */
}
