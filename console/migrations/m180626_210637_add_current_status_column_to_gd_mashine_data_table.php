<?php

use yii\db\Migration;

/**
 * Handles adding current_status to table `gd_mashine_data`.
 */
class m180626_210637_add_current_status_column_to_gd_mashine_data_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('gd_mashine_data', 'current_status', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('gd_mashine_data', 'current_status');
    }
}
