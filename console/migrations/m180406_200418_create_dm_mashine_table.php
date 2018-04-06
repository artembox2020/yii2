<?php

use yii\db\Migration;

/**
 * Handles the creation of table `dm_mashine`.
 * Has foreign keys to the tables:
 *
 * - `imei`
 */
class m180406_200418_create_dm_mashine_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('dm_mashine', [
            'id' => $this->primaryKey(),
            'imei_id' => $this->integer()->notNull(),
            'serial_number' => $this->integer(),
            'number_device' => $this->integer(),
            'level_signal' => $this->integer(),
            'bill_cash' => $this->integer(),
            'status' => $this->integer()
        ]);

        // creates index for column `imei_id`
        $this->createIndex(
            'idx-dm_mashine-imei_id',
            'dm_mashine',
            'imei_id'
        );

        // add foreign key for table `imei`
        $this->addForeignKey(
            'fk-dm_mashine-imei_id',
            'dm_mashine',
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
            'fk-dm_mashine-imei_id',
            'dm_mashine'
        );

        // drops index for column `imei_id`
        $this->dropIndex(
            'idx-dm_mashine-imei_id',
            'dm_mashine'
        );

        $this->dropTable('dm_mashine');
    }
}
