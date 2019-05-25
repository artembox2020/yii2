<?php

use yii\db\Migration;

/**
 * Handles adding level_signal to table `imei`.
 */
class m181114_093117_add_level_signal_column_to_imei_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('imei', 'level_signal', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('imei', 'level_signal');
    }
}
