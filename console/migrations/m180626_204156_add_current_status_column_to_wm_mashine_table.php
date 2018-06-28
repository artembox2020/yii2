<?php

use yii\db\Migration;

/**
 * Handles adding current_status to table `wm_mashine`.
 */
class m180626_204156_add_current_status_column_to_wm_mashine_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('wm_mashine', 'current_status', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('wm_mashine', 'current_status');
    }
}
