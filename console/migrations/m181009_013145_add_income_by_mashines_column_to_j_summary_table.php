<?php

use yii\db\Migration;

/**
 * Handles adding income_by_mashines to table `j_summary`.
 */
class m181009_013145_add_income_by_mashines_column_to_j_summary_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
         $this->addColumn('j_summary', 'income_by_mashines', $this->text()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('j_summary', 'income_by_mashines');
    }
}
