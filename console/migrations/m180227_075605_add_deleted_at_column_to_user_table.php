<?php

use yii\db\Migration;

/**
 * Handles adding deleted_at to table `user`.
 */
class m180227_075605_add_deleted_at_column_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'deleted_at', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'deleted_at');
    }
}
