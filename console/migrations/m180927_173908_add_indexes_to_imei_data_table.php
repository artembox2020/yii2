<?php

use yii\db\Migration;

/**
 * Class m180927_173908_add_indexes_to_imei_data_table
 */
class m180927_173908_add_indexes_to_imei_data_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // creates index for columns `fireproof_residue, created_at, imei_id`
        $this->createIndex(
            'idx-imei_data-fireproof',
            'imei_data',
            [
                'fireproof_residue',
                'created_at',
                'imei_id',
            ],
            false
        );

        // creates index for columns `created_at, imei_id`
        $this->createIndex(
            'idx-imei_data-created_at-imei_id',
            'imei_data',
            [
                'created_at',
                'imei_id',
            ],
            false
        );

        // creates index for column `created_at`
        $this->createIndex(
            'idx-imei_data-created_at',
            'imei_data',
            'created_at',
            false
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // deletes index `idx-imei_data-fireproof` for imei_data table
        $this->dropIndex(
            'idx-imei_data-fireproof',
            'imei_data'
        );

        // deletes index `idx-imei_data-created_at-imei_id` for imei_data table
        $this->dropIndex(
            'idx-imei_data-created_at-imei_id',
            'imei_data'
        );

        // deletes index `idx-imei_data-created_at` for imei_data table
        $this->dropIndex(
            'idx-imei_data-created_at',
            'imei_data'
        );
 
    }
}
