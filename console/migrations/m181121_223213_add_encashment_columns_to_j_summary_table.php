<?php

use yii\db\Migration;

/**
 * Handles adding encashment columns to table `j_summary`.
 */
class m181121_223213_add_encashment_columns_to_j_summary_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('j_summary', 'encashment_date', $this->integer());
        $this->addColumn('j_summary', 'encashment_sum', $this->double());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('j_summary', 'encashment_date');
        $this->dropColumn('j_summary', 'encashment_sum');
    }
}
