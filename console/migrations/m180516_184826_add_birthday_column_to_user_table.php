<?php

use yii\db\Migration;

/**
 * Handles adding birthday to table `user`.
 */
class m180516_184826_add_birthday_column_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'birthday', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'birthday');
    }
}
