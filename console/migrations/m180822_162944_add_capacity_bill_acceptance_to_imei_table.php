<?php

use yii\db\Migration;

/**
 * Class m180822_162944_add_capacity_bill_acceptance_to_imei_table
 */
class m180822_162944_add_capacity_bill_acceptance_to_imei_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
         $this->addColumn('imei', 'capacity_bill_acceptance', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('imei', 'capacity_bill_acceptance');
    }
}
