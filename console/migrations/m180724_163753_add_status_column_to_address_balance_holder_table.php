<?php

use yii\db\Migration;

/**
 * Handles adding status to table `address_balance_holder`.
 */
class m180724_163753_add_status_column_to_address_balance_holder_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('address_balance_holder', 'status', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('address_balance_holder', 'status');
    }
}
