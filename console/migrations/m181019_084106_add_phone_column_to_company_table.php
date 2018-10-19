<?php

use yii\db\Migration;

/**
 * Handles adding phone to table `company`.
 */
class m181019_084106_add_phone_column_to_company_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('company', 'phone', $this->string(100));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('company', 'phone');
    }
}
