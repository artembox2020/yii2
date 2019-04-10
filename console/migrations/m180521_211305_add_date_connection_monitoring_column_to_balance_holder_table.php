<?php

use yii\db\Migration;

/**
 * Handles adding date_connection_monitoring to table `balance_holder`.
 */
class m180521_211305_add_date_connection_monitoring_column_to_balance_holder_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('balance_holder', 'date_connection_monitoring', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('balance_holder', 'date_connection_monitoring');
    }
}
