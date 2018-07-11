<?php

use yii\db\Migration;

/**
 * Handles adding company_id to table `other_contact_person`.
 */
class m180710_111856_add_company_id_column_to_other_contact_person_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('other_contact_person', 'company_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('other_contact_person', 'company_id');
    }
}
