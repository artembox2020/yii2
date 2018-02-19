<?php

use yii\db\Migration;

/**
 * Handles the creation of table `org`.
 */
class m180219_182121_create_org_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('org', [
            'id' => $this->primaryKey(),
            'name_org' => $this->string(100)->notNull(),
            'logo_path' => $this->string(255)->notNull(),
            'desc' => $this->string(200)->notNull(),
            'user_id' => $this->string(200)->notNull(),
            'admin_id' => $this->string(200)->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('org');
    }
}
