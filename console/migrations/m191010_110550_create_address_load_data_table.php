<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%address_load_data}}`.
 */
class m191010_110550_create_address_load_data_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('address_load_data', [
            'id' => $this->primaryKey(),
            'start' => $this->integer(),
            'end' => $this->integer(),
            'address_id' => $this->integer(),
            'value' => $this->integer(),
        ]);

        // creates index for column `address_id`
        $this->createIndex(
            'idx-address_load_data-address_id',
            'address_load_data',
            [
                'address_id'
            ],
            false
        );

        // creates index for columns `address_id, start`
        $this->createIndex(
            'idx-address_load_data-address_id-start',
            'address_load_data',
            [
                'address_id',
                'start'
            ],
            false
        );

        // creates index for columns `address_id, start, end`
        $this->createIndex(
            'idx-address_load_data-address_id-start-end',
            'address_load_data',
            [
                'address_id',
                'start',
                'end'
            ],
            true
        );

        // creates index for columns `address_id, end`
        $this->createIndex(
            'idx-address_load_data-address_id-end',
            'address_load_data',
            [
                'address_id',
                'end'
            ],
            false
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('address_load_data');
    }
}
