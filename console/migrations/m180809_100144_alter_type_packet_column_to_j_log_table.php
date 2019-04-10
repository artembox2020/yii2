<?php

use yii\db\Migration;

/**
 * Class m180809_100144_alter_type_packet_column_to_j_log_table
 */
class m180809_100144_alter_type_packet_column_to_j_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('j_log', 'type_packet', $this->integer(11));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('j_log', 'type_packet', $this->string(250)->null());
    }
}
