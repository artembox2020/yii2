<?php

use yii\db\Migration;

/**
 * Class m181123_054708_change_income_by_mashines_column_to_j_summary_table
 */
class m181123_054708_change_income_by_mashines_column_to_j_summary_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('j_summary', 'income_by_mashines', $this->string(255)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('j_summary', 'income_by_mashines', $this->string(100)->null());  
    }
}
