<?php

use yii\db\Migration;

/**
 * Handles adding date_purchase to table `wm_mashine`.
 */
class m180709_034707_add_date_purchase_column_to_wm_mashine_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('wm_mashine', 'date_purchase', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('wm_mashine', 'date_purchase');
    }
}
