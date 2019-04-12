<?php

use yii\db\Migration;

/**
 * Handles adding damage_idle_hours to table `{{%j_summary}}`.
 */
class m190411_141222_add_damage_idle_hours_column_to_j_summary_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('j_summary', 'damageIdleHours', $this->double()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('j_summary', 'damageIdleHours');
    }
}
