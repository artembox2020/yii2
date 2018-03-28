<?php

use yii\db\Migration;

/**
 * Handles the creation of table `balance_holder`.
 */
class m180324_201252_create_balance_holder_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('balance_holder', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255),
            'city' => $this->string(255),
            'address' => $this->string(255),
            'phone' => $this->string(100),
            'contact_person' => $this->string(255),
            'company_id' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'is_deleted' => $this->boolean(),
            'deleted_at' => $this->integer()
        ]);

        $this->createIndex(
            'idx-balance_holder-company_id',
            'balance_holder',
            'company_id'
        );

        // add foreign key for table `company`
        $this->addForeignKey(
            'fk-balance_holder-company_id',
            'balance_holder',
            'company_id',
            'company',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('balance_holder');
    }
}
