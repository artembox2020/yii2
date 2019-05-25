<?php

use yii\db\Migration;

/**
 * Handles adding position to table `balance_holder`.
 */
class m180521_213000_add_position_column_to_balance_holder_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('balance_holder', 'position', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('balance_holder', 'position');
    }
}
