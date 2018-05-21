<?php

use yii\db\Migration;

/**
 * Handles adding date_start_cooperation to table `balance_holder`.
 */
class m180521_204919_add_date_start_cooperation_column_to_balance_holder_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('balance_holder', 'date_start_cooperation', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('balance_holder', 'date_start_cooperation');
    }
}
