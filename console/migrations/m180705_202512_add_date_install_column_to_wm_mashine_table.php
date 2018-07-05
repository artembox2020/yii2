<?php

use yii\db\Migration;

/**
 * Handles adding date_install to table `wm_mashine`.
 */
class m180705_202512_add_date_install_column_to_wm_mashine_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('wm_mashine', 'date_install', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('wm_mashine', 'date_install');
    }
}
