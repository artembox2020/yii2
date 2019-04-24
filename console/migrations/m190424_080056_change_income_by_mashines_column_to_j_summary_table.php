<?php

use yii\db\Migration;

/**
 * Class m190424_080056_change_income_by_mashines_column_to_j_summary_table
 */
class m190424_080056_change_income_by_mashines_column_to_j_summary_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('j_summary', 'income_by_mashines', $this->text()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('j_summary', 'income_by_mashines', $this->string(255)->null());
    }
}
