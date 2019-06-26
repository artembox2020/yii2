<?php

use yii\db\Migration;

/**
 * Class m190626_192944_change_operation_time_column_in_transactions_table
 */
class m190626_192944_change_operation_time_column_in_transactions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('transactions', 'operation_time', 'text');
    }

    public function down()
    {
        $this->alterColumn('transactions', 'operation_time', 'dateTime');
    }
}
