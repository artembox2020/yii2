<?php

use yii\db\Migration;

/**
 * Handles adding ping to table `imei`.
 */
class m180730_125618_add_ping_column_to_imei_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
         $this->addColumn('imei', 'ping', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('imei', 'ping');
    }
}
