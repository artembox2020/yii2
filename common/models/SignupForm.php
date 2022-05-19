<?php

namespace common\models;

use yii\base\Model;
use common\models\User;

class SignupForm extends Model{
    
    public $username;
    public $password;
    
    public function rules() {
        return [
            [['username', 'password'], 'required', 'message' => 'Fill in the field'],
            ['username', 'unique', 'targetClass' => User::className(),  'message' => 'Field busy'],
        ];
    }
    
    public function attributeLabels() {
        return [
            'username' => 'Login',
            'password' => 'Password',
        ];
    }
    
}