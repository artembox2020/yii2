<?php

use yii\db\Migration;

/**
 * Handles the creation of table `address_balance_holder`.
 * Has foreign keys to the tables:
 *
 * - `balance_holder`
 */
class m180324_203706_create_address_balance_holder_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('address_balance_holder', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255),
            'address' => $this->string(255),
            'floor' => $this->integer(11),
            'balance_holder_id' => $this->integer()->notNull(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'is_deleted' => $this->boolean(),
            'deleted_at' => $this->integer()
        ]);

        // creates index for column `balance_holder_id`
        $this->createIndex(
            'idx-address_balance_holder-balance_holder_id',
            'address_balance_holder',
            'balance_holder_id'
        );

        // add foreign key for table `balance_holder`
        $this->addForeignKey(
            'fk-address_balance_holder-balance_holder_id',
            'address_balance_holder',
            'balance_holder_id',
            'balance_holder',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `balance_holder`
        $this->dropForeignKey(
            'fk-address_balance_holder-balance_holder_id',
            'address_balance_holder'
        );

        // drops index for column `balance_holder_id`
        $this->dropIndex(
            'idx-address_balance_holder-balance_holder_id',
            'address_balance_holder'
        );

        $this->dropTable('address_balance_holder');
    }
}
