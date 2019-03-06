<?php

use yii\db\Migration;

/**
 * Class m190305_003800_add_indexes_to_cb_log_table
 */
class m190305_003800_add_indexes_to_cb_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // creates index for column `unix_time_offset`
        $this->createIndex(
            'idx-cb_log-unix_time_offset',
            'cb_log',
            [
                'unix_time_offset'
            ],
            false
        );

        // creates index for column `number`
        $this->createIndex(
            'idx-cb_log-number',
            'cb_log',
            [
                'number',
            ],
            false
        );
        
        // creates index for columns `company_id, number`
        $this->createIndex(
            'idx-cb_log-company_id-number',
            'cb_log',
            [
                'company_id',
                'number',
            ],
            false
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // deletes index `idx-cb_log-unix_time_offset`
        $this->dropIndex(
            'idx-cb_log-unix_time_offset',
            'cb_log'
        );

        // deletes index `idx-cb_log-number`
        $this->dropIndex(
            'idx-cb_log-number',
            'cb_log'
        );

        // deletes index `idx-cb_log-company_id-number`
        $this->dropIndex(
            'idx-cb_log-company_id-number',
            'cb_log'
        );
    }
}
