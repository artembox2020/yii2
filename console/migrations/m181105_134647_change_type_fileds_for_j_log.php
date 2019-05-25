<?php

use yii\db\Migration;

/**
 * Class m181105_134647_change_type_fileds_for_j_log
 */
class m181105_134647_change_type_fileds_for_j_log extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('j_log', 'imei', $this->string(64)->null());
        $this->alterColumn('j_log', 'packet', $this->string(255)->null());
        $this->alterColumn('j_log', 'events', $this->string(100)->null()); 
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('j_log', 'imei', $this->string(250)->null());
        $this->alterColumn('j_log', 'packet', $this->text()->null());
        $this->alterColumn('j_log', 'events', $this->text()->null()); 
    }
}
