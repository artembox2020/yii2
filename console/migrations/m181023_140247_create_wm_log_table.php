<?php

use yii\db\Migration;

/**
 * Handles the creation of table `wm_log`.
 */
class m181023_140247_create_wm_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('wm_log', [
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
            'idx-wm_log-imei_id',
            'wm_log',
            'imei_id'
        );

        // add foreign key for table `imei`
        $this->addForeignKey(
            'fk-wm_log-imei_id',
            'wm_log',
            'imei_id',
            'imei',
            'id',
            'CASCADE'
        );

        // creates index for column `company_id`
        $this->createIndex(
            'idx-wm_log-company_id',
            'wm_log',
            'company_id'
        );

        // add foreign key for table `company`
        $this->addForeignKey(
            'fk-wm_log-company_id',
            'wm_log',
            'company_id',
            'company',
            'id',
            'CASCADE'
        );

        // creates index for column `address_id`
        $this->createIndex(
            'idx-wm_log-address_id',
            'wm_log',
            'address_id'
        );

        // add foreign key for table `wm_log`
        $this->addForeignKey(
            'fk-wm_log-address_id',
            'wm_log',
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
            'fk-wm_log-address_id',
            'wm_log'
        );

        // drops index for column `address_id`
        $this->dropIndex(
            'idx-wm_log-address_id',
            'wm_log'
        );

        // drops foreign key for table `imei`
        $this->dropForeignKey(
            'fk-wm_log-imei_id',
            'wm_log'
        );

        // drops index for column `imei_id`
        $this->dropIndex(
            'idx-wm_log-imei_id',
            'wm_log'
        );

        $this->dropTable('wm_log');
    }
}
