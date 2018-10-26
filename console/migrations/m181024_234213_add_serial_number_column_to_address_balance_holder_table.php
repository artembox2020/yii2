<?php

use yii\db\Migration;

/**
 * Handles adding serial_number to table `address_balance_holder`.
 */
class m181024_234213_add_serial_number_column_to_address_balance_holder_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('address_balance_holder', 'serial_column', $this->integer(11));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('address_balance_holder', 'serial_column');
    }
}
