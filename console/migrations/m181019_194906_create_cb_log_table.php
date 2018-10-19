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
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('cb_log');
    }
}
