<?php

use yii\db\Migration;

/**
 * Handles the creation of table `imei_action`.
 */
class m190215_225503_create_imei_action_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('imei_action', [
            'id' => $this->primaryKey(),
            'imei_id' => $this->integer()->notNull(),
            'imei' => $this->string(50),
            'action' => $this->string(128),
            'unix_time_offset' => $this->integer(),
            'is_active' => $this->boolean(),
            'is_cancelled' => $this->boolean(),
            'is_deleted' => $this->boolean(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'deleted_at' => $this->integer()
        ]);

        // creates index for column `imei_id`
        $this->createIndex(
            'idx-imei_action-imei_id',
            'imei_action',
            'imei_id'
        );

        // add foreign key for table `imei`
        $this->addForeignKey(
            'fk-imei_action-imei_id',
            'imei_action',
            'imei_id',
            'imei',
            'id',
            'CASCADE'
        );

        // creates index for column `action`
        $this->createIndex(
            'idx-imei_action-action',
            'imei_action',
            'action'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `imei`
        $this->dropForeignKey(
            'fk-imei_action-imei_id',
            'imei_action'
        );

        // drops index for column `imei_id`
        $this->dropIndex(
            'idx-imei_action-imei_id',
            'imei_action'
        );

        // drops index for column `action`
        $this->dropIndex(
            'idx-imei_action-action',
            'imei_action'
        );

        $this->dropTable('imei_action');
    }
}
