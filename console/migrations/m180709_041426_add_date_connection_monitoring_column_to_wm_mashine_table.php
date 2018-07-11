<?php

use yii\db\Migration;

/**
 * Handles adding date_connection_monitoring to table `wm_mashine`.
 */
class m180709_041426_add_date_connection_monitoring_column_to_wm_mashine_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('wm_mashine', 'date_connection_monitoring', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('wm_mashine', 'date_connection_monitoring');
    }
}
