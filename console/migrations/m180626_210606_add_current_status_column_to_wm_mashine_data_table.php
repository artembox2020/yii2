<?php

use yii\db\Migration;

/**
 * Handles adding current_status to table `wm_mashine_data`.
 */
class m180626_210606_add_current_status_column_to_wm_mashine_data_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('wm_mashine_data', 'current_status', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('wm_mashine_data', 'current_status');
    }
}
