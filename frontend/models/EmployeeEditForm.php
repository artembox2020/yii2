<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class EmployeeEditForm extends Model
{
    public $username;
    public $position;
    public $birthday;
    public $roles;

    private $model;

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('backend', 'Username'),
            'position' => Yii::t('backend', 'Position'),
            'birthday' => Yii::t('backend', 'Birthday'),
//            'email' => Yii::t('backend', 'Email'),
//            'password' => Yii::t('backend', 'Password'),
//            'status' => Yii::t('backend', 'Status'),
            'roles' => Yii::t('backend', 'Roles'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function getModel()
    {
        if (!$this->model) {
            $this->model = new User();
        }

        return $this->model;
    }

    /**
     * @inheritdoc
     */
    public function setModel($model)
    {
        $this->username = $model->username;
        $this->position = $model->position;
        $this->birthday = $model->birthday;
        $this->model = $model;
        $this->roles = ArrayHelper::getColumn(Yii::$app->authManager->getRolesByUser($model->getId()), 'name');

        return $this->model;
    }

    public function save()
    {
        $model = $this->getModel();
        $model->username = $this->username;
        $model->email = $this->email;
        $model->status = $this->status;
    }
}
