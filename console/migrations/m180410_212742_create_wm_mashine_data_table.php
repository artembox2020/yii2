<?php

use yii\db\Migration;

/**
 * Handles the creation of table `wm_mashine_data`.
 * Has foreign keys to the tables:
 *
 * - `wm_mashine`
 */
class m180410_212742_create_wm_mashine_data_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('wm_mashine_data', [
            'id' => $this->primaryKey(),
            'wm_mashine_id' => $this->integer()->notNull(),
            'type_mashine' => $this->string(),
            'number_device' => $this->integer(),
            'level_signal' => $this->integer(),
            'bill_cash' => $this->integer(),
            'door_position' => $this->integer(),
            'door_block_led' => $this->integer(),
            'status' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'is_deleted' => $this->boolean(),
            'deleted_at' => $this->integer(),
        ]);

        // creates index for column `wm_mashine_id`
        $this->createIndex(
            'idx-wm_mashine_data-wm_mashine_id',
            'wm_mashine_data',
            'wm_mashine_id'
        );

        // add foreign key for table `wm_mashine`
        $this->addForeignKey(
            'fk-wm_mashine_data-wm_mashine_id',
            'wm_mashine_data',
            'wm_mashine_id',
            'wm_mashine',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `wm_mashine`
        $this->dropForeignKey(
            'fk-wm_mashine_data-wm_mashine_id',
            'wm_mashine_data'
        );

        // drops index for column `wm_mashine_id`
        $this->dropIndex(
            'idx-wm_mashine_data-wm_mashine_id',
            'wm_mashine_data'
        );

        $this->dropTable('wm_mashine_data');
    }
}
