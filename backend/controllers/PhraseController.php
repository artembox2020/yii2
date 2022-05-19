<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use common\models\Phrase;
use common\models\PhraseTrans;
use common\widgets\phrase\PhraseWidget;
use common\widgets\phrase\PhraseListWidget;

class PhraseController extends Controller
{
    public function actionIndex()
    {
        if (empty(Yii::$app->request->post()['PhraseForm'])) {

            return $this->render('index');
        }

        return PhraseWidget::createPhrase();
    }

    public function actionPostBind()
    {
        if (empty(Yii::$app->request->post()['DynamicForm'])) {

            return $this->actionIndex();
        }

        $data = Yii::$app->request->post()['DynamicForm'];

        return PhraseListWidget::createPostBind($data);
    }
}