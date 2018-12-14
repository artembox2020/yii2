<?php

use yii\db\Migration;

/**
 * Handles adding date_end to table `j_log`.
 */
class m181213_220612_add_date_end_column_to_j_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('j_log', 'date_end', $this->string(128));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('j_log', 'date_end');
    }
}
