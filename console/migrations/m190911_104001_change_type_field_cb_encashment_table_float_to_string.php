<?php

use yii\db\Migration;

/**
 * Class m190911_104001_change_type_field_cb_encashment_table_float_to_string
 */
class m190911_104001_change_type_field_cb_encashment_table_float_to_string extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('cb_encashment', 'collection_counter', 'decimal');
        $this->alterColumn('cb_encashment', 'last_collection_counter', 'decimal');
        $this->alterColumn('cb_encashment', 'fireproof_counter_hrn', 'decimal');
        $this->alterColumn('cb_encashment', 'recount_amount', 'decimal');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190911_104001_change_type_field_cb_encashment_table_float_to_string cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190911_104001_change_type_field_cb_encashment_table_float_to_string cannot be reverted.\n";

        return false;
    }
    */
}
