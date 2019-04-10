<?php

use yii\db\Migration;

/**
 * Handles adding address_id to table `j_summary`.
 */
class m181022_214908_add_address_id_column_to_j_summary_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('j_summary', 'address_id', $this->integer(11));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
         $this->dropColumn('j_summary', 'address_id');
    }
}
