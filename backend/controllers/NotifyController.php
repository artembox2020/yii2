<?php

namespace backend\controllers;

use yii\web\Controller;

class NotifyController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}