<?php

use yii\db\Migration;

/**
 * Class m190201_032603_add_index_to_imei_data_table
 */
class m190201_032603_add_index_to_imei_data_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // creates index `idx-imei_data-is_deleted-imei_id-money_in_banknotes-created_at`
        $this->createIndex(
            'idx-imei_data-is_deleted-imei_id-money_in_banknotes-created_at',
            'imei_data',
            [
                'is_deleted',
                'imei_id',
                'money_in_banknotes',
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
        // deletes index `idx-imei_data-is_deleted-imei_id-money_in_banknotes-created_at`
        $this->dropIndex(
            'idx-imei_data-is_deleted-imei_id-money_in_banknotes-created_at',
            'imei_data'
        );
    }
}
