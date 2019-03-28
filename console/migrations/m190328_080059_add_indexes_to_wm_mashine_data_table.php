<?php

use yii\db\Migration;

/**
 * Class m190328_080059_add_indexes_to_wm_mashine_data_table
 */
class m190328_080059_add_indexes_to_wm_mashine_data_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // creates index for column `current_status`
        $this->createIndex(
            'idx-wm_mashine_data-current_status',
            'wm_mashine_data',
            [
                'current_status'
            ],
            false
        );
        
        // creates index for columns `created_at, current_status`
        $this->createIndex(
            'idx-wm_mashine_data-created_at-current_status',
            'wm_mashine_data',
            [
                'created_at',
                'current_status'
            ],
            false
        );

        // creates index for columns `mashine_id, is_deleted, created_at`
        $this->createIndex(
            'idx-wm_mashine_data-mashine_id-is_deleted-created_at',
            'wm_mashine_data',
            [
                'mashine_id',
                'is_deleted',
                'created_at'
            ],
            false
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // deletes index `idx-wm_mashine_data-current_status`
        $this->dropIndex(
            'idx-wm_mashine_data-current_status',
            'wm_mashine_data'
        );

        // deletes index `idx-wm_mashine_data-created_at-current_status`
        $this->dropIndex(
            'idx-wm_mashine_data-created_at-current_status',
            'wm_mashine_data'
        );

        // deletes index `idx-wm_mashine_data-mashine_id-is_deleted-created_at`
        $this->dropIndex(
            'idx-wm_mashine_data-mashine_id-is_deleted-created_at',
            'wm_mashine_data'
        );
    }
}
