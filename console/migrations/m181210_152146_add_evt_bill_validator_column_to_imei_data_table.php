<?php

use yii\db\Migration;

/**
 * Handles adding evt_bill_validator to table `imei_data`.
 */
class m181210_152146_add_evt_bill_validator_column_to_imei_data_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('imei_data', 'evt_bill_validator', $this->integer()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('imei_data', 'evt_bill_validator');
    }
}
