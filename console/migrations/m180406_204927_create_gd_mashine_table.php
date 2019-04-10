<?php

use yii\db\Migration;

/**
 * Handles the creation of table `gd_mashine`.
 * Has foreign keys to the tables:
 *
 * - `imei`
 */
class m180406_204927_create_gd_mashine_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('gd_mashine', [
            'id' => $this->primaryKey(),
            'imei_id' => $this->integer()->notNull(),
            'company_id' => $this->integer()->notNull(),
            'balance_holder_id' => $this->integer()->notNull(),
            'address_id' => $this->integer()->notNull(),
            'type_mashine' => $this->string(),
            'serial_number' => $this->string(100),
            'gel_in_tank' => $this->integer(),
            'bill_cash' => $this->integer(),
            'status' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'is_deleted' => $this->boolean(),
            'deleted_at' => $this->integer()
        ]);

        // creates index for column `imei_id`
        $this->createIndex(
            'idx-gd_mashine-imei_id',
            'gd_mashine',
            'imei_id'
        );

        // add foreign key for table `imei`
        $this->addForeignKey(
            'fk-gd_mashine-imei_id',
            'gd_mashine',
            'imei_id',
            'imei',
            'id',
            'CASCADE'
        );

        // creates index for column `company_id`
        $this->createIndex(
            'idx-gd_mashine-company_id',
            'gd_mashine',
            'company_id'
        );

        // add foreign key for table `company`
        $this->addForeignKey(
            'fk-gd_mashine-company_id',
            'gd_mashine',
            'company_id',
            'company',
            'id',
            'CASCADE'
        );

        // creates index for column `address_id`
        $this->createIndex(
            'idx-gd_mashine-address_id',
            'gd_mashine',
            'address_id'
        );

        // add foreign key for table `address_balance_holder`
        $this->addForeignKey(
            'fk-gd_mashine-address_id',
            'gd_mashine',
            'address_id',
            'address_balance_holder',
            'id',
            'CASCADE'
        );

        // creates index for column `balance_holder_id`
        $this->createIndex(
            'idx-gd_mashine-balance_holder_id',
            'gd_mashine',
            'balance_holder_id'
        );

        // add foreign key for table `balance_holder`
        $this->addForeignKey(
            'fk-gd_mashine-balance_holder_id',
            'gd_mashine',
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
        // drops foreign key for table `imei`
        $this->dropForeignKey(
            'fk-gd_mashine-imei_id',
            'gd_mashine'
        );

        // drops index for column `imei_id`
        $this->dropIndex(
            'idx-gd_mashine-imei_id',
            'gd_mashine'
        );

        // drops foreign key for table `company`
        $this->dropForeignKey(
            'fk-gd_mashine-company_id',
            'gd_mashine'
        );

        // drops index for column `company_id`
        $this->dropIndex(
            'idx-gd_mashine-company_id',
            'gd_mashine'
        );

        $this->dropTable('gd_mashine');
    }
}
