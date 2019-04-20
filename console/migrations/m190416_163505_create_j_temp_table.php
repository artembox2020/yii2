<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%j_temp}}`.
 */
class m190416_163505_create_j_temp_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('j_temp', [
            'id' => $this->primaryKey(),
            'type' => $this->string(50),
            'param_type' => $this->string(50),
            'entity_id' => $this->integer(),
            'start' => $this->integer(),
            'end' => $this->integer(),
            'value' => $this->string(255)->null(),
            'other' => $this->string(255)->null()
        ]);

        // creates index for column `end`
        $this->createIndex(
            'idx-j_temp-end',
            'j_temp',
            [
                'end'
            ],
            false
        );

        // creates index for columns `type, param_type, entity_id`
        $this->createIndex(
            'idx-j_temp-type-param_type-entity_id',
            'j_temp',
            [
                'type',
                'param_type',
                'entity_id'
            ],
            false
        );

        // creates index for columns `type, param_type, entity_id, start, end`
        $this->createIndex(
            'idx-j_temp-type-param_type-entity_id-start-end',
            'j_temp',
            [
                'type',
                'param_type',
                'entity_id',
                'start',
                'end'
            ],
            false
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // deletes index `idx-j_temp-end`
        $this->dropIndex(
            'idx-j_temp-end',
            'j_temp'
        );

        // deletes index `idx-j_temp-type-param_type-entity_id`
        $this->dropIndex(
            'idx-j_temp-type-param_type-entity_id',
            'j_temp'
        );

        // deletes index `idx-j_temp-type-param_type-entity_id-start-end`
        $this->dropIndex(
            'idx-j_temp-type-param_type-entity_id-start-end',
            'j_temp'
        );

        $this->dropTable('j_temp');
    }
}
