<?php

use yii\db\Migration;

/**
 * Handles adding display to table `wm_mashine`.
 */
class m180809_094825_add_display_column_to_wm_mashine_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('wm_mashine', 'display', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('wm_mashine', 'display');
    }
}
