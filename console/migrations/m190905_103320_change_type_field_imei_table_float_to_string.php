<?php

use yii\db\Migration;

/**
 * Class m190905_103320_change_type_field_imei_table_float_to_string
 */
class m190905_103320_change_type_field_imei_table_float_to_string extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('imei', 'pcb_version', 'string');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190905_103320_change_type_field_imei_table_float_to_string cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190905_103320_change_type_field_imei_table_float_to_string cannot be reverted.\n";

        return false;
    }
    */
}
