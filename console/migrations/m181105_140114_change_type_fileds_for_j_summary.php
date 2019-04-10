<?php

use yii\db\Migration;

/**
 * Class m181105_140114_change_type_fileds_for_j_summary
 */
class m181105_140114_change_type_fileds_for_j_summary extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
         $this->alterColumn('j_summary', 'income_by_mashines', $this->string(100)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
         $this->alterColumn('j_summary', 'income_by_mashines', $this->text()->null());
    }
}
