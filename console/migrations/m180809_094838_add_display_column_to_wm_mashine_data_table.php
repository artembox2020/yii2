<?php

use yii\db\Migration;

/**
 * Handles adding display to table `wm_mashine_data`.
 */
class m180809_094838_add_display_column_to_wm_mashine_data_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('wm_mashine_data', 'display', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('wm_mashine_data', 'display');
    }
}
