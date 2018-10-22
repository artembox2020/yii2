<?php

namespace frontend\controllers;

use yii\web\Controller;

class LwController extends Controller
{
    public function actionIndex($p)
    {
        echo $p;
    }
}
