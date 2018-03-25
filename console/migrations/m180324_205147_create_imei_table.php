<?php

use yii\db\Migration;

/**
 * Handles the creation of table `imei`.
 * Has foreign keys to the tables:
 *
 * - `floor`
 */
class m180324_205147_create_imei_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('imei', [
            'id' => $this->primaryKey(),
            'imei' => $this->integer(255),
            'floor_id' => $this->integer()->notNull(),
            'created_at' => $this->integer(),
            'is_deleted' => $this->boolean(),
            'deleted_at' => $this->integer()
        ]);

        // creates index for column `floor_id`
        $this->createIndex(
            'idx-imei-floor_id',
            'imei',
            'floor_id'
        );

        // add foreign key for table `floor`
        $this->addForeignKey(
            'fk-imei-floor_id',
            'imei',
            'floor_id',
            'floor',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `floor`
        $this->dropForeignKey(
            'fk-imei-floor_id',
            'imei'
        );

        // drops index for column `floor_id`
        $this->dropIndex(
            'idx-imei-floor_id',
            'imei'
        );

        $this->dropTable('imei');
    }
}
