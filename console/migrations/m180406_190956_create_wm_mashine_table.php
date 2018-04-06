<?php

use yii\db\Migration;

/**
 * Handles the creation of table `wm_mashine`.
 * Has foreign keys to the tables:
 *
 * - `imei_data`
 */
class m180406_190956_create_wm_mashine_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('wm_mashine', [
            'id' => $this->primaryKey(),
            'imei_id' => $this->integer()->notNull(),
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

        // creates index for column `imei_id`
        $this->createIndex(
            'idx-wm_mashine-imei_id',
            'wm_mashine',
            'imei_id'
        );

        // add foreign key for table `imei`
        $this->addForeignKey(
            'fk-wm_mashine-imei_id',
            'wm_mashine',
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
            'fk-wm_mashine-imei_id',
            'wm_mashine'
        );

        // drops index for column `imei_id`
        $this->dropIndex(
            'idx-wm_mashine-imei_id',
            'wm_mashine'
        );

        $this->dropTable('wm_mashine');
    }
}
