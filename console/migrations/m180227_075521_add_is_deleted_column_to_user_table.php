<?php

use yii\db\Migration;

/**
 * Handles adding is_deleted to table `user`.
 */
class m180227_075521_add_is_deleted_column_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'is_deleted', $this->boolean());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'is_deleted');
    }
}
