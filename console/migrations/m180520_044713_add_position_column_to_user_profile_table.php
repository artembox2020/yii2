<?php

use yii\db\Migration;

/**
 * Handles adding position to table `user_profile`.
 */
class m180520_044713_add_position_column_to_user_profile_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user_profile', 'position', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user_profile', 'position');
    }
}
