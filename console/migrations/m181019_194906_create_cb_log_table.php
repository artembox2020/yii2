<?php

use yii\db\Migration;

/**
 * Handles the creation of table `cb_log`.
 */
class m181019_194906_create_cb_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('cb_log', [
            'id' => $this->primaryKey(),
            'company_id' => $this->integer()->notNull(),
            'address_id' => $this->integer()->notNull(),
            'imei_id' => $this->integer()->notNull(),
            'date' => $this->integer(),
            'imei' => $this->string(50),
            'device' => $this->string(11),
            'unix_time_offset' => $this->integer(),
            'status' => $this->integer(),
            'signal' => $this->integer(),
            'number' => $this->integer(),
            'price' => $this->float(),
            'refill_amount' => $this->float(),
            'account_money' => $this->float(),
            'collection_counter' => $this->float(),
            'washing_mode' => $this->integer(),
            'fireproof_counter_hrn' => $this->float(),
            'wash_temperature' => $this->integer(),
            'rate' => $this->double(),
            'fireproof_counter_card' => $this->float(),
            'spin_type' => $this->integer(),
            'notes_billiards_pcs' => $this->double(),
            'prewash' => $this->double(),
            'rinsing' => $this->double(),
            'intensive_wash' => $this->double(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'is_deleted' => $this->boolean(),
            'deleted_at' => $this->integer()
        ]);

        // creates index for column `imei_id`
        $this->createIndex(
            'idx-cb_log-imei_id',
            'cb_log',
            'imei_id'
        );

        // add foreign key for table `imei`
        $this->addForeignKey(
            'fk-cb_log-imei_id',
            'cb_log',
            'imei_id',
            'imei',
            'id',
            'CASCADE'
        );

        // creates index for column `company_id`
        $this->createIndex(
            'idx-cb_log-company_id',
            'cb_log',
            'company_id'
        );

        // add foreign key for table `company`
        $this->addForeignKey(
            'fk-cb_log-company_id',
            'cb_log',
            'company_id',
            'company',
            'id',
            'CASCADE'
        );

        // creates index for column `address_id`
        $this->createIndex(
            'idx-cb_log-address_id',
            'cb_log',
            'address_id'
        );

        // add foreign key for table `cb_log`
        $this->addForeignKey(
            'fk-cb_log-address_id',
            'cb_log',
            'address_id',
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
        $this->dropForeignKey(
            'fk-cb_log-address_id',
            'cb_log'
        );

        // drops index for column `address_id`
        $this->dropIndex(
            'idx-cb_log-address_id',
            'cb_log'
        );

        // drops foreign key for table `imei`
        $this->dropForeignKey(
            'fk-cb_log-imei_id',
            'cb_log'
        );

        // drops index for column `imei_id`
        $this->dropIndex(
            'idx-cb_log-imei_id',
            'cb_log'
        );

        $this->dropTable('cb_log');
    }
}
