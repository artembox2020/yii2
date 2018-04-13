<?php

use yii\db\Migration;

/**
 * Handles the creation of table `gd_mashine_data`.
 * Has foreign keys to the tables:
 *
 * - `imei`
 */
class m180410_212805_create_gd_mashine_data_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('gd_mashine_data', [
            'id' => $this->primaryKey(),
            'imei_id' => $this->integer()->notNull(),
            'type_mashine' => $this->string(),
            'gel_in_tank' => $this->integer(),
            'bill_cash' => $this->integer(),
            'status' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'is_deleted' => $this->boolean(),
            'deleted_at' => $this->integer()
        ]);

        // creates index for column `imei_id`
        $this->createIndex(
            'idx-gd_mashine_data-imei_id',
            'gd_mashine_data',
            'imei_id'
        );

        // add foreign key for table `imei`
        $this->addForeignKey(
            'fk-gd_mashine_data-imei_id',
            'gd_mashine_data',
            'imei_id',
            'imei',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `imei`
        $this->dropForeignKey(
            'fk-gd_mashine_data-imei_id',
            'gd_mashine_data'
        );

        // drops index for column `imei_id`
        $this->dropIndex(
            'idx-gd_mashine_data-imei_id',
            'gd_mashine_data'
        );

        $this->dropTable('gd_mashine_data');
    }
}
