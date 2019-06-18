<?php

use yii\db\Migration;

/**
 * Handles the creation of table `orders`.
 */
class m190618_183252_create_orders_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('orders', [
            'id' => $this->primaryKey(),
            'order_uuid' => $this->text(),
            'card_no' => $this->integer(),
            'amount' => $this->decimal(4,2),
            'status' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('orders');
    }
}
