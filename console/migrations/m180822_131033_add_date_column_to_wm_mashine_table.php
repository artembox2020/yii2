<?php

use yii\db\Migration;

/**
 * Handles adding date to table `wm_mashine`.
 */
class m180822_131033_add_date_column_to_wm_mashine_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('wm_mashine', 'ping', $this->integer()->unsigned());
        $this->addColumn('wm_mashine_data', 'ping', $this->integer()->unsigned());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('wm_mashine', 'ping');
        $this->dropColumn('wm_mashine_data', 'ping');
    }
}
