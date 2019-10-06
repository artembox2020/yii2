<?php

use yii\db\Migration;

/**
 * Handles adding static_address_and_static_floor to table `{{%address_balance_holder}}`.
 */
class m191004_012556_add_static_address_and_static_floor_columns_to_address_balance_holder_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('address_balance_holder', 'static_address', $this->string()->null());
        $this->addColumn('address_balance_holder', 'static_floor', $this->string()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('address_balance_holder', 'static_address');
        $this->dropColumn('address_balance_holder', 'static_floor');
    }
}