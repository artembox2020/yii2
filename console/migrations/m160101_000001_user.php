<?php

use yii\db\Migration;

class m160101_000001_user extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string(255)->notNull(),
            'auth_key' => $this->string(32)->notNull(),
            'access_token' => $this->string(255),
            'password_hash' => $this->string(255)->notNull(),
            'email' => $this->string(255)->notNull(),
            'status' => $this->smallInteger()->notNull(),
            'company_id' => $this->integer(11),
            'ip' => $this->string(128),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'action_at' => $this->integer(),
            'deleted_at' => $this->integer(),
            'is_deleted' => $this->boolean(),
            'other' => $this->string(255)
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
    }
}
