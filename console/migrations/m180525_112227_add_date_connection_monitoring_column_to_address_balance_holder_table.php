<?php

use yii\db\Migration;

/**
 * Handles adding date_connection_monitoring to table `address_balance_holder`.
 */
class m180525_112227_add_date_connection_monitoring_column_to_address_balance_holder_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('address_balance_holder', 'date_connection_monitoring', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('address_balance_holder', 'date_connection_monitoring');
    }
}
