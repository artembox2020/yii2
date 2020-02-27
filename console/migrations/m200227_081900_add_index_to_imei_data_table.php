<?php

use yii\db\Migration;

/**
 * Class m200227_081900_add_index_to_imei_data_table
 */
class m200227_081900_add_index_to_imei_data_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        /** adds index to imei_data */
        $this->createIndex(
            'idx-imei_data-imei_id-is_deleted-date-updated_at',
            'imei_data',
            [
                'imei_id',
                'is_deleted',
                'date',
                'updated_at'
            ],
            false
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        /** drops index to imei_data */
        $this->dropIndex(
            'idx-imei_data-imei_id-is_deleted-date-updated_at',
            'imei_data'
        );
    }
}
