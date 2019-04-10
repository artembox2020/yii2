<?php

use yii\db\Migration;

/**
 * Handles adding img to table `balance_holders`.
 */
class m180716_223722_add_img_column_to_balance_holders_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
         $this->addColumn('balance_holder', 'img', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('balance_holder', 'img');
    }
}
