<?php

use yii\db\Migration;

/**
 * Handles adding allHours to table `j_summary`.
 */
class m181119_235920_add_allHours_column_to_j_summary_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('j_summary', 'allHours', $this->double());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('j_summary', 'allHours');
    }
}
