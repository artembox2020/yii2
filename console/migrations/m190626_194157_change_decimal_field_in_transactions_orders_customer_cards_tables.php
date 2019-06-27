<?php

use yii\db\Migration;

/**
 * Class m190626_194157_change_decimal_field_in_transactions_orders_customer_cards_tables
 */
class m190626_194157_change_decimal_field_in_transactions_orders_customer_cards_tables extends Migration
{
    public function up()
    {
        $this->alterColumn('customer_cards', 'balance', 'decimal(15,2)');
        $this->alterColumn('transactions', 'amount', 'decimal(15,2)');
        $this->alterColumn('orders', 'amount', 'decimal(15,2)');
    }

    public function down()
    {
        $this->alterColumn('customer_cards', 'balance', 'decimal(4,2)');
        $this->alterColumn('transactions', 'amount', 'decimal(4,2)');
        $this->alterColumn('orders', 'amount', 'decimal(4,2)');
    }
}
