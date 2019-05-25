<?php

use yii\db\Migration;

/**
 * Handles adding model to table `wm_mashine`.
 */
class m180705_202427_add_model_column_to_wm_mashine_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('wm_mashine', 'model', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('wm_mashine', 'model');
    }
}
