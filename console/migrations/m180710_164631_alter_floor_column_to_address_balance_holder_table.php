<?php

use yii\db\Migration;

/**
 * Class m180710_164631_alter_floor_column_to_address_balance_holder_table
 */
class m180710_164631_alter_floor_column_to_address_balance_holder_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('address_balance_holder', 'floor', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('address_balance_holder', 'floor', $this->integer(11));
    }
}
