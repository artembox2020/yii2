<?php

use yii\db\Migration;

/**
 * Handles adding total_cash to table `{{%wm_mashine_data}}`.
 */
class m190911_065855_add_total_cash_column_to_wm_mashine_data_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%wm_mashine_data}}', 'total_cash', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%wm_mashine_data}}', 'total_cash');
    }
}
