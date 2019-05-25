<?php

use yii\db\Migration;

/**
 * Handles adding number_of_floors to table `address_balance_holder`.
 */
class m180523_125016_add_number_of_floors_column_to_address_balance_holder_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('address_balance_holder', 'number_of_floors', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('address_balance_holder', 'number_of_floors');
    }
}
