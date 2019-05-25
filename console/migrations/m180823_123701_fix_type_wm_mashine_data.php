<?php

use yii\db\Migration;

/**
 * Class m180823_123701_fix_type_wm_mashine_data
 */
class m180823_123701_fix_type_wm_mashine_data extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('wm_mashine_data', 'number_device', $this->double());
        $this->alterColumn('wm_mashine_data', 'level_signal', $this->integer());
        $this->alterColumn('wm_mashine_data', 'bill_cash', $this->float());
        $this->alterColumn('wm_mashine_data', 'door_position', $this->double());
        $this->alterColumn('wm_mashine_data', 'door_block_led', $this->double());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('wm_mashine_data', 'number_device', $this->integer());
        $this->alterColumn('wm_mashine_data', 'level_signal', $this->integer());
        $this->alterColumn('wm_mashine_data', 'bill_cash', $this->integer());
        $this->alterColumn('wm_mashine_data', 'door_position', $this->integer());
        $this->alterColumn('wm_mashine_data', 'door_block_led', $this->integer());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180823_123701_fix_type_wm_mashine_data cannot be reverted.\n";

        return false;
    }
    */
}
