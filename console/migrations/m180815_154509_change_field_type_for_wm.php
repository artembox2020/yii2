<?php

use yii\db\Migration;

/**
 * Class m180815_154509_change_field_type_for_wm
 */
class m180815_154509_change_field_type_for_wm extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('wm_mashine', 'number_device', $this->double());
        $this->alterColumn('wm_mashine', 'bill_cash', $this->float());
        $this->alterColumn('wm_mashine', 'door_position', $this->double());
        $this->alterColumn('wm_mashine', 'door_block_led', $this->double());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('wm_mashine', 'number_device', $this->integer());
        $this->alterColumn('wm_mashine', 'bill_cash', $this->integer());
        $this->alterColumn('wm_mashine', 'door_position', $this->integer());
        $this->alterColumn('wm_mashine', 'door_block_led', $this->integer());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180815_154509_change_field_type_for_wm cannot be reverted.\n";

        return false;
    }
    */
}
