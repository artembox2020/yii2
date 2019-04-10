<?php

use yii\db\Migration;

/**
 * Handles the creation of table `wm_mashine`.
 * Has foreign keys to the tables:
 *
 * - `imei_data`
 */
class m180406_190956_create_wm_mashine_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('wm_mashine', [
            'id' => $this->primaryKey(),
            'company_id' => $this->integer()->notNull(),
            'balance_holder_id' => $this->integer()->notNull(),
            'address_id' => $this->integer()->notNull(),
            'imei_id' => $this->integer()->notNull(),
            'type_mashine' => $this->string(),
            'serial_number' => $this->string(100),
            'number_device' => $this->integer(),
            'level_signal' => $this->integer(),
            'bill_cash' => $this->integer(),
            'door_position' => $this->integer(),
            'door_block_led' => $this->integer(),
            'status' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'is_deleted' => $this->boolean(),
            'deleted_at' => $this->integer(),
        ]);

        // creates index for column `imei_id`
        $this->createIndex(
            'idx-wm_mashine-imei_id',
            'wm_mashine',
            'imei_id'
        );

        // add foreign key for table `imei`
        $this->addForeignKey(
            'fk-wm_mashine-imei_id',
            'wm_mashine',
            'imei_id',
            'imei',
            'id',
            'CASCADE'
        );

        // creates index for column `company_id`
        $this->createIndex(
            'idx-wm_mashine-company_id',
            'wm_mashine',
            'company_id'
        );

        // add foreign key for table `company`
        $this->addForeignKey(
            'fk-wm_mashine-company_id',
            'wm_mashine',
            'company_id',
            'company',
            'id',
            'CASCADE'
        );

        // creates index for column `address_id`
        $this->createIndex(
            'idx-wm_mashine-address_id',
            'wm_mashine',
            'address_id'
        );

        // add foreign key for table `wm_mashine`
        $this->addForeignKey(
            'fk-wm_mashine-address_id',
            'wm_mashine',
            'address_id',
            'address_balance_holder',
            'id',
            'CASCADE'
        );

        // creates index for column `balance_holder_id`
        $this->createIndex(
            'idx-wm_mashine-balance_holder_id',
            'wm_mashine',
            'balance_holder_id'
        );

        // add foreign key for table `balance_holder`
        $this->addForeignKey(
            'fk-wm_mashine-balance_holder_id',
            'wm_mashine',
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
        $this->dropForeignKey(
            'fk-wm_mashine-address_id',
            'wm_mashine'
        );

        // drops index for column `address_id`
        $this->dropIndex(
            'idx-wm_mashine-address_id',
            'wm_mashine'
        );

        // drops foreign key for table `imei`
        $this->dropForeignKey(
            'fk-wm_mashine-imei_id',
            'wm_mashine'
        );

        // drops index for column `imei_id`
        $this->dropIndex(
            'idx-wm_mashine-imei_id',
            'wm_mashine'
        );

        // drops foreign key for table `balance_holder`
        $this->dropForeignKey(
            'fk-wm_mashine-balance_holder_id',
            'wm_mashine'
        );

        // drops index for column `balance_holder_id`
        $this->dropIndex(
            'idx-wm_mashine-balance_holder_id',
            'wm_mashine'
        );

        $this->dropTable('wm_mashine');
    }
}
