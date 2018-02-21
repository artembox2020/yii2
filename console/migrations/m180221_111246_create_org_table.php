<?php

use yii\db\Migration;

/**
 * Handles the creation of table `org`.
 */
class m180221_111246_create_org_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('org', [
            'id' => $this->primaryKey(),
            'name_org' => $this->string(100),
            'logo_path' => $this->string(255),
            'desc' => $this->text(),
            'user_id' => $this->integer(25),
            'admin_id' => $this->integer(25),
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
