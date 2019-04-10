<?php

use yii\db\Migration;

/**
 * Class m181115_015233_change_index_to_j_summary_table
 */
class m181115_015233_change_index_to_j_summary_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropIndex('idx-j_summary-imei_id-start_timestamp-end_timestamp', 'j_summary');

        // creates index for columns `imei_id, start_timestamp, end_timestamp`
        $this->createIndex(
            'idx-j_summary-imei_id-start_timestamp-end_timestamp',
            'j_summary',
            [
                'imei_id',
                'start_timestamp',
                'end_timestamp',
            ],
            false
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
       // silence gold
    }
}
