<?php

use yii\db\Migration;

/**
 * Class m181113_161740_add_is_cancelled_fileld_to_j_summary_table
 */
class m181113_161740_add_is_cancelled_fileld_to_j_summary_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
         $this->addColumn('j_summary', 'is_cancelled', $this->boolean());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('j_summary', 'is_cancelled');
    }
}
