<?php

use yii\db\Migration;

/**
 * Class m180830_150700_add_property_inventory_number_to_wm_mashine
 */
class m180830_150700_add_property_inventory_number_to_wm_mashine extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('wm_mashine', 'inventory_number', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('wm_mashine', 'inventory_number');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180830_150700_add_property_inventory_number_to_wm_mashine cannot be reverted.\n";

        return false;
    }
    */
}
