<?php

use yii\db\Migration;

/**
 * Handles adding traffic to table `{{%imei}}`.
 */
class m190713_211032_add_traffic_column_to_imei_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('imei', 'traffic', $this->float(3));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('imei', 'traffic');
    }
}
