<?php

use yii\db\Migration;

/**
 * Handles adding recount_amount to table `cb_log`.
 */
class m190118_231305_add_recount_amount_column_to_cb_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('cb_log', 'recount_amount', $this->float()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('cb_log', 'recount_amount');
    }
}
