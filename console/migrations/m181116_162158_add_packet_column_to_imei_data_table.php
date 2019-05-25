<?php

use yii\db\Migration;

/**
 * Handles adding packet to table `imei_data`.
 */
class m181116_162158_add_packet_column_to_imei_data_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('imei_data', 'packet', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('imei_data', 'packet');
    }
}
