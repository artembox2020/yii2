<?php

use yii\db\Migration;

/**
 * Handles the creation of table `address_imei_data`.
 */
class m181030_120234_create_address_imei_data_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('address_imei_data', [
            'id' => $this->primaryKey(),
            'address_id' => $this->integer(),
            'imei_id' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'is_deleted' => $this->boolean(),
            'deleted_at' => $this->integer()
        ]);

        // creates index for address_imei_data table
        $this->createIndex(
            'idx-address_imei_data-created_at',
            'address_imei_data',
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
        // deletes index for address_imei_data table
        $this->dropIndex(
            'idx-address_imei_data-created_at',
            'address_imei_data'
        );

        $this->dropTable('address_imei_data');
    }
}
