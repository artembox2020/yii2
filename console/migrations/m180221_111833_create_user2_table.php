<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user2`.
 */
class m180221_111833_create_user2_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user2', [
            'id' => $this->primaryKey(),
            'username' => $this->string(255),
            'auth_key' => $this->string(32)->null(),
            'access_token' => $this->string(255),
            'password_hash' => $this->string(255),
            'email' => $this->string(255),
            'status' => $this->smallInteger(6),
            'ip' => $this->string(128)->null(),
            'created_at' => $this->integer(11)->null(),
            'updated_at' => $this->integer(11)->null(),
            'action_at' => $this->integer(11)->null()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('user2');
    }
}
