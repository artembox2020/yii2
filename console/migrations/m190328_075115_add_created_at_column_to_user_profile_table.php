<?php

use yii\db\Migration;

/**
 * Handles adding created_at to table `{{%user_profile}}`.
 */
class m190328_075115_add_created_at_column_to_user_profile_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user_profile}}', 'created_at', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user_profile}}', 'created_at');
    }
}
