<?php

use yii\db\Migration;

/**
 * Handles adding cb_version to table `imei_data`.
 */
class m181116_162211_add_cb_version_column_to_imei_data_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('imei_data', 'cb_version', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('imei_data', 'cb_version');
    }
}
