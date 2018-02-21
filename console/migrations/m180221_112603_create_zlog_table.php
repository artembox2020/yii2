<?php

use yii\db\Migration;

/**
 * Handles the creation of table `zlog`.
 */
class m180221_112603_create_zlog_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('zlog', [
            'id' => $this->primaryKey(),
            'r_date' => $this->dateTime()->null(),
            'edate' => $this->string(250)->null(),
            'imei' => $this->string(250)->null(),
            'type' => $this->string(250)->null(),
            'status_dev' => $this->string(250)->null(),
            'ch_uah' => $this->string(250)->null(),
            'ch_map' => $this->string(250)->null(),
            'ch_incasso' => $this->string(250)->null(),
            'col_cup' => $this->string(250)->null(),
            'tarif' => $this->string(250)->null(),
            'num_dev' => $this->string(250)->null(),
            'lmodem' => $this->string(250)->null(),
            'price' => $this->string(250)->null(),
            'col_mon' => $this->string(250)->null(),
            'rezim' => $this->string(250)->null(),
            'tstir' => $this->string(250)->null(),
            'otzim_type' => $this->string(250)->null(),
            'p_stir' => $this->string(250)->null(),
            'polosk' => $this->string(250)->null(),
            'intensiv' => $this->string(250)->null(),
            'sv' => $this->string(250)->null(),
            'nch' => $this->string(250)->null(),
            'col_gel' => $this->string(250)->null(),
            'by_gel' => $this->string(250)->null(),
            'esum' => $this->string(250)->null()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('zlog');
    }
}
