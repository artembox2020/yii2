<?php

use yii\db\Migration;

/**
 * Handles adding image to table `{{%wm_mashine}}`.
 */
class m190625_191514_add_image_columns_to_wm_mashine_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('wm_mashine', 'img1', $this->string());
        $this->addColumn('wm_mashine', 'img2', $this->string());
        $this->addColumn('wm_mashine', 'img3', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('wm_mashine', 'img1');
        $this->dropColumn('wm_mashine', 'img2');
        $this->dropColumn('wm_mashine', 'img3');
    }
}