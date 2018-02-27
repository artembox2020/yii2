<?php

use yii\db\Migration;

/**
 * Handles the creation of table `company`.
 */
class m180223_083615_create_company_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('company', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100),
            'img' => $this->string(255),
            'description' => $this->text(),
            'website' => $this->string(255),
            'created_at' => $this->integer(),
            'is_deleted' => $this->boolean(),
            'deleted_at' => $this->integer(),
        ]);

        $this->addForeignKey(
            'fk_company',
            '{{%user}}',
            'company_id',
            '{{%company}}',
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
            'fk_company',
            'user'
        );

        $this->dropTable('company');
    }
}
