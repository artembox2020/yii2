<?php

use yii\db\Migration;

/**
 * Class m181123_034154_alter_idleHours_column_to_j_summary_table
 */
class m181123_034154_alter_idleHours_column_to_j_summary_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('j_summary', 'idleHours', $this->double());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('j_summary', 'idleHours', $this->integer());   
    }
}
