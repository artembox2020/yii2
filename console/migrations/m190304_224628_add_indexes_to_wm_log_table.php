<?php

use yii\db\Migration;

/**
 * Class m190304_224628_add_indexes_to_wm_log_table
 */
class m190304_224628_add_indexes_to_wm_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // creates index for column `unix_time_offset`
        $this->createIndex(
            'idx-wm_log-unix_time_offset',
            'wm_log',
            [
                'unix_time_offset'
            ],
            false
        );

        // creates index for column `number`
        $this->createIndex(
            'idx-wm_log-number',
            'wm_log',
            [
                'number',
            ],
            false
        );

        // creates index for columns `company_id, number`
        $this->createIndex(
            'idx-wm_log-company_id-number',
            'wm_log',
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
        // deletes index `idx-wm_log-unix_time_offset`
        $this->dropIndex(
            'idx-wm_log-unix_time_offset',
            'wm_log'
        );

        // deletes index `idx-wm_log-number`
        $this->dropIndex(
            'idx-wm_log-number',
            'wm_log'
        );

        // deletes index `idx-wm_log-company_id-number`
        $this->dropIndex(
            'idx-wm_log-company_id-number',
            'wm_log'
        );
    }
}
