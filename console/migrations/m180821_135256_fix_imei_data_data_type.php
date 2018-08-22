<?php

use yii\db\Migration;

/**
 * Class m180821_135256_fix_imei_data_data_type
 */
class m180821_135256_fix_imei_data_data_type extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('imei_data', 'imei', $this->string());
        $this->alterColumn('imei_data', 'level_signal', $this->double());
        $this->alterColumn('imei_data', 'on_modem_account', $this->float());
        $this->alterColumn('imei_data', 'in_banknotes', $this->double());
        $this->alterColumn('imei_data', 'money_in_banknotes', $this->float());
        $this->alterColumn('imei_data', 'fireproof_residue', $this->float());
        $this->alterColumn('imei_data', 'price_regim', $this->double());

        $this->addColumn('imei_data', 'date', $this->integer()->unsigned());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('imei_data', 'imei', $this->string());
        $this->alterColumn('imei_data', 'level_signal', $this->integer());
        $this->alterColumn('imei_data', 'on_modem_account', $this->integer());
        $this->alterColumn('imei_data', 'in_banknotes', $this->integer());
        $this->alterColumn('imei_data', 'money_in_banknotes', $this->integer());
        $this->alterColumn('imei_data', 'fireproof_residue', $this->integer());
        $this->alterColumn('imei_data', 'price_regim', $this->integer());

        $this->dropColumn('imei_data', 'date');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180821_135256_fix_imei_data_data_type cannot be reverted.\n";

        return false;
    }
    */
}
