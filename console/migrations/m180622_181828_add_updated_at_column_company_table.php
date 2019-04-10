<?php

use yii\db\Migration;

/**
 * Class m180622_181828_add_updated_at_column_company_table
 */
class m180622_181828_add_updated_at_column_company_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('company', 'updated_at', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('company', 'updated_at');
    }
}
