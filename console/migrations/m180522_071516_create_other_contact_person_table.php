<?php

use yii\db\Migration;

/**
 * Handles the creation of table `other_contact_person`.
 * Has foreign keys to the tables:
 *
 * - `balance_holder`
 */
class m180522_071516_create_other_contact_person_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('other_contact_person', [
            'id' => $this->primaryKey(),
            'balance_holder_id' => $this->integer()->notNull(),
            'name' => $this->string(),
            'position' => $this->string(),
            'phone' => $this->string(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'is_deleted' => $this->boolean(),
            'deleted_at' => $this->integer()
        ]);

        // creates index for column `balance_holder_id`
        $this->createIndex(
            'idx-other_contact_person-balance_holder_id',
            'other_contact_person',
            'balance_holder_id'
        );

        // add foreign key for table `balance_holder`
        $this->addForeignKey(
            'fk-other_contact_person-balance_holder_id',
            'other_contact_person',
            'balance_holder_id',
            'balance_holder',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `balance_holder`
        $this->dropForeignKey(
            'fk-other_contact_person-balance_holder_id',
            'other_contact_person'
        );

        // drops index for column `balance_holder_id`
        $this->dropIndex(
            'idx-other_contact_person-balance_holder_id',
            'other_contact_person'
        );

        $this->dropTable('other_contact_person');
    }
}
