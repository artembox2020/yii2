<?php

use yii\db\Migration;

/**
 * Handles adding brand to table `wm_mashine`.
 */
class m180705_202356_add_brand_column_to_wm_mashine_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('wm_mashine', 'brand', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('wm_mashine', 'brand');
    }
}
