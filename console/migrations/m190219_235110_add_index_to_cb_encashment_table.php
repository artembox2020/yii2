<?php

use yii\db\Migration;

/**
 * Class m190219_235110_add_index_to_cb_encashment_table
 */
class m190219_235110_add_index_to_cb_encashment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // creates index for columns `imei_id, unix_time_offset`
        $this->createIndex(
            'idx-cb_encashment-imei_id-unix_time_offset',
            'cb_encashment',
            [
                'imei_id',
                'unix_time_offset'
            ],
            true
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // deletes index `idx-cb_encashment-imei_id-unix_time_offset` for cb_encashment table
        $this->dropIndex(
            'idx-cb_encashment-imei_id-unix_time_offset',
            'cb_encashment'
        );
    }
}
