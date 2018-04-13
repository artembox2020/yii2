<?php

namespace frontend\controllers;

use common\models\User;
use Yii;

class MonitoringController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $user = User::findOne(Yii::$app->user->id);

        if (!empty($user->company)) {
            $users = $user->company->users;
            $model = $user->company;
            $balanceHolders = $model->balanceHolders;
        } else {
            return $this->redirect('account/sign-in/login');
        }

        return $this->render('index', [
            'model' => $model,
            'users' => $users,
            'balanceHolders' => $balanceHolders,
        ]);
    }

}
