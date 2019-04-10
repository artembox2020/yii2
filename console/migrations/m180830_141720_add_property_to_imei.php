<?php

use yii\db\Migration;

/**
 * Class m180830_141720_add_property_to_imei
 */
class m180830_141720_add_property_to_imei extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('imei', 'number_channel', $this->float());
        $this->addColumn('imei', 'pcb_version', $this->float());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('imei', 'number_channel');
        $this->dropColumn('imei', 'pcb_version');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180830_141720_add_property_to_imei cannot be reverted.\n";

        return false;
    }
    */
}
