<?php

use yii\db\Migration;

/**
 * Handles the creation of table `gd_mashine`.
 * Has foreign keys to the tables:
 *
 * - `imei`
 */
class m180406_204927_create_gd_mashine_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('gd_mashine', [
            'id' => $this->primaryKey(),
            'imei_id' => $this->integer()->notNull(),
            'type_mashine' => $this->string(),
            'serial_number' => $this->integer(),
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
            'idx-gd_mashine-imei_id',
            'gd_mashine',
            'imei_id'
        );

        // add foreign key for table `imei`
        $this->addForeignKey(
            'fk-gd_mashine-imei_id',
            'gd_mashine',
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
            'fk-gd_mashine-imei_id',
            'gd_mashine'
        );

        // drops index for column `imei_id`
        $this->dropIndex(
            'idx-gd_mashine-imei_id',
            'gd_mashine'
        );

        $this->dropTable('gd_mashine');
    }
}
