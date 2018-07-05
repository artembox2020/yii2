<?php

use yii\db\Migration;

/**
 * Handles adding date_build to table `wm_mashine`.
 */
class m180705_202542_add_date_build_column_to_wm_mashine_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('wm_mashine', 'date_build', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('wm_mashine', 'date_build');
    }
}
