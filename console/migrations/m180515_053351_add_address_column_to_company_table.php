<?php

use yii\db\Migration;

/**
 * Handles adding address to table `company`.
 */
class m180515_053351_add_address_column_to_company_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('company', 'address', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('company', 'address');
    }
}
