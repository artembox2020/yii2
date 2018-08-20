<?php

use yii\db\Migration;

/**
 * Class m180820_084700_change_type_fields_for_imei_table
 */
class m180820_084700_change_type_fields_for_imei_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('imei', 'firmware_version_cpu', $this->string(100));
        $this->addColumn('imei', 'firmware_6lowpan', $this->float());
        $this->alterColumn('imei', 'critical_amount', $this->float());
        $this->alterColumn('imei', 'time_out', $this->double());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('imei', 'firmware_version_cpu');
        $this->dropColumn('imei', 'firmware_6lowpan');
        $this->alterColumn('imei', 'critical_amount', $this->integer());
        $this->alterColumn('imei', 'time_out', $this->integer());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180820_084700_change_type_fields_for_imei_table cannot be reverted.\n";

        return false;
    }
    */
}
