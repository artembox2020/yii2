<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%transactions}}`.
 */
class m190606_160646_create_transactions_table extends Migration
{

    public function safeUp()
    {
        $this->createTable('transactions', [
            'id' => $this->primaryKey(),
            'card_id' => $this->integer(),
            'imei' => $this->string(50),
            'operation' => $this->integer(),
            'amount' => $this->decimal(4,2),
            'comment' => $this->text(),
            'operation_time' => $this->dateTime(),
            'created_at' => $this->integer(),
        ]);
    }
    
    public function safeDown()
    {
        $this->dropTable('transactions');
    }
}
