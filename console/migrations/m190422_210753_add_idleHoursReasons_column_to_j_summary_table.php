<?php

use yii\db\Migration;

/**
 * Handles adding idleHoursReasons to table `{{%j_summary}}`.
 */
class m190422_210753_add_idleHoursReasons_column_to_j_summary_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('j_summary', 'idleHoursReasons', $this->string(255)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
         $this->dropColumn('j_summary', 'idleHoursReasons');
    }
}
