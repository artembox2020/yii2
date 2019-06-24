<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%customer_cards}}`.
 */
class m190606_155303_create_customer_cards_table extends Migration
{

    public function safeUp()
    {
        $this->createTable('customer_cards', [
            'id' => $this->primaryKey(),
            'card_no' => $this->integer(),
            'balance' => $this->decimal(4,2),
            'discount' => $this->integer(),
            'status' => $this->integer(),
            'created_at' => $this->integer(),
            'deleted_at' => $this->integer(),
            'is_deleted' => $this->boolean(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('customer_cards');
    }
}
