<?php

use yii\db\Migration;

/**
 * Handles the creation of table `machine`.
 * Has foreign keys to the tables:
 *
 * - `imei`
 */
class m180324_223353_create_machine_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('machine', [
            'id' => $this->primaryKey(),
            'imei_id' => $this->integer()->notNull(),
            'num_dev' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'is_deleted' => $this->boolean(),
            'deleted_at' => $this->integer()
        ]);

        // creates index for column `imei_id`
        $this->createIndex(
            'idx-machine-imei_id',
            'machine',
            'imei_id'
        );

        // add foreign key for table `imei`
        $this->addForeignKey(
            'fk-machine-imei_id',
            'machine',
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
            'fk-machine-imei_id',
            'machine'
        );

        // drops index for column `imei_id`
        $this->dropIndex(
            'idx-machine-imei_id',
            'machine'
        );

        $this->dropTable('machine');
    }
}
