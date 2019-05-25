<?php

use yii\db\Migration;

/**
 * Class m181222_141642_add_last_collection_counter_and_banknote_face_values_to_cb_log_table
 */
class m181222_141642_add_last_collection_counter_and_banknote_face_values_to_cb_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('cb_log', 'last_collection_counter', $this->float()->null());
        $this->addColumn('cb_log', 'banknote_face_values', $this->string()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('cb_log', 'last_collection_counter');
        $this->dropColumn('cb_log', 'banknote_face_values');
    }
}
