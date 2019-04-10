<?php

use yii\db\Migration;

/**
 * Handles adding date_inserted to table `address_balance_holder`.
 */
class m180525_111052_add_date_inserted_column_to_address_balance_holder_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('address_balance_holder', 'date_inserted', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('address_balance_holder', 'date_inserted');
    }
}
