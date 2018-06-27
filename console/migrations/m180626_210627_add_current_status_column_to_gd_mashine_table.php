<?php

use yii\db\Migration;

/**
 * Handles adding current_status to table `gd_mashine`.
 */
class m180626_210627_add_current_status_column_to_gd_mashine_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('gd_mashine', 'current_status', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('gd_mashine', 'current_status');
    }
}
