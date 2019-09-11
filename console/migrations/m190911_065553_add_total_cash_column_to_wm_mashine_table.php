<?php

use yii\db\Migration;

/**
 * Handles adding total_cash to table `{{%wm_mashine}}`.
 */
class m190911_065553_add_total_cash_column_to_wm_mashine_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%wm_mashine}}', 'total_cash', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%wm_mashine}}', 'total_cash');
    }
}
