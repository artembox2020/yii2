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
            'imei_id' => $this->integer()->notNull(),
            'date' => $this->integer(),
            'imei' => $this->string(50),
            'unix_time_offset' => $this->integer(),
            'status' => $this->integer(),
            'fireproof_counter_hrn' => $this->float(),
            'fireproof_counter_card' => $this->float(),
            'collection_counter' => $this->float(),
            'notes_billiards_pcs' => $this->double(),
            'rate' => $this->double(),
            'refill_amount' => $this->float(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'is_deleted' => $this->boolean(),
            'deleted_at' => $this->integer()
        ]);

        // creates index for column `company_id`
        $this->createIndex(
            'idx-cb_log-imei_id',
            'cb_log',
            'imei_id'
        );

        // add foreign key for table `company`
        $this->addForeignKey(
            'fk-cb_log-imei_id',
            'cb_log',
            'imei_id',
            'imei',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-imei-id',
            'imei'
        );

        // drops index for column `address_id`
        $this->dropIndex(
            'idx-imei-imei_id',
            'imei'
        );

        $this->dropTable('cb_log');
    }
}
