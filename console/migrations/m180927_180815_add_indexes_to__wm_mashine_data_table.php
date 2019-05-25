<?php

use yii\db\Migration;

/**
 * Class m180927_180815_add_indexes_to__wm_mashine_data_table
 */
class m180927_180815_add_indexes_to__wm_mashine_data_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // creates index for columns `mashine_id, created_at`
        $this->createIndex(
            'idx-wm_mashine_data-mashine_id-created_at',
            'wm_mashine_data',
            [
                'mashine_id',
                'created_at',
            ],
            false
        );

        // creates index for column `created_at`
        $this->createIndex(
            'idx-wm_mashine_data-created_at',
            'wm_mashine_data',
            [
                'created_at',
            ],
            false
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // deletes index `idx-wm_mashine_data-mashine_id-created_at` for wm_mashine_data table
        $this->dropIndex(
            'idx-wm_mashine_data-mashine_id-created_at',
            'wm_mashine_data'
        );

        // deletes index `idx-wm_mashine_data-created_at` for wm_mashine_data table
        $this->dropIndex(
            'idx-wm_mashine_data-created_at',
            'wm_mashine_data'
        );
    }
}
