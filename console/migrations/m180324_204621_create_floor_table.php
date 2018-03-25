<?php

use yii\db\Migration;

/**
 * Handles the creation of table `floor`.
 * Has foreign keys to the tables:
 *
 * - `address_balance_holder`
 */
class m180324_204621_create_floor_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('floor', [
            'id' => $this->primaryKey(),
            'floor_number' => $this->integer(),
            'address_balance_holder_id' => $this->integer()->notNull(),
            'created_at' => $this->integer(),
            'is_deleted' => $this->boolean(),
            'deleted_at' => $this->integer()
        ]);

        // creates index for column `address_balance_holder_id`
        $this->createIndex(
            'idx-floor-address_balance_holder_id',
            'floor',
            'address_balance_holder_id'
        );

        // add foreign key for table `address_balance_holder`
        $this->addForeignKey(
            'fk-floor-address_balance_holder_id',
            'floor',
            'address_balance_holder_id',
            'address_balance_holder',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `address_balance_holder`
        $this->dropForeignKey(
            'fk-floor-address_balance_holder_id',
            'floor'
        );

        // drops index for column `address_balance_holder_id`
        $this->dropIndex(
            'idx-floor-address_balance_holder_id',
            'floor'
        );

        $this->dropTable('floor');
    }
}
