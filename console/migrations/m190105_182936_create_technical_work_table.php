<?php

use yii\db\Migration;

/**
 * Handles the creation of table `technical_work`.
 */
class m190105_182936_create_technical_work_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('technical_work', [
            'id' => $this->primaryKey(),
            'address_id' => $this->integer(),
            'machine_id' => $this->integer()->notNull(),
            'inventory_number' => $this->integer(),
            'technical_work_data' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'is_deleted' => $this->boolean(),
            'deleted_at' => $this->integer(),
        ]);

        $this->createIndex(
            'idx-technical_work-machine_id',
            'technical_work',
            'machine_id'
        );

        $this->addForeignKey(
            'fk-technical_work-machine_id',
            'technical_work',
            'machine_id',
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
        $this->dropForeignKey(
            'fk-technical_work-machine_id',
            'technical_work'
        );

        $this->dropIndex(
            'idx-technical_work-machine_id',
            'technical_work'
        );

        $this->dropTable('technical_work');
    }
}
