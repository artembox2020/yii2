<?php

use yii\db\Migration;

/**
 * Class m181130_121007_add_on_modem_account_field_to_imei_table
 */
class m181130_121007_add_on_modem_account_field_to_imei_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('imei', 'on_modem_account', $this->float()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('imei', 'on_modem_account');
    }
}
