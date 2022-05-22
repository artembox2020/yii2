<?php

namespace console\models;

use Yii;

/**
 * This is the model class for table "cron_task".
 *
 * @property int $id
 * @property string $controller
 * @property string $action
 * @property string|null $params
 * @property int $timeout
 * @property string $created_at
 * @property string $updated_at
 */
class CronTask extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cron_task';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['controller', 'action', 'timeout'], 'required'],
            [['params', 'created_at', 'updated_at'], 'safe'],
            [['timeout'], 'integer'],
            [['controller', 'action'], 'string', 'max' => 80],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'controller' => 'Controller',
            'action' => 'Action',
            'params' => 'Params',
            'timeout' => 'Timeout',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
