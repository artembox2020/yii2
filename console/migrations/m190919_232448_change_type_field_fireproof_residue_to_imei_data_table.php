<?php

use yii\db\Migration;

/**
 * Class m190919_232448_change_type_field_fireproof_residue_to_imei_data_table
 */
class m190919_232448_change_type_field_fireproof_residue_to_imei_data_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('imei_data', 'fireproof_residue', 'decimal');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190919_232448_change_type_field_fireproof_residue_to_imei_data_table cannot be reverted.\n";

        return false;
    }
}
