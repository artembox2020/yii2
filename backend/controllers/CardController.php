<?php

namespace backend\controllers;

use backend\models\search\TransactionSearch;
use frontend\models\Transactions;
use Yii;
use backend\models\search\CardSearch;
use yii\web\Controller;

class CardController extends Controller
{
    public function actionIndex()
    {
        $cards = new CardSearch();
        $dataProvider = $cards->search(Yii::$app->request->queryParams);

        return $this->render('index', ['cards' => $cards, 'dataProvider' => $dataProvider]);
    }

    public function actionUpdate($id)
    {
        $transactions = new TransactionSearch();
        $dataProvider = $transactions->search($id, Yii::$app->request->queryParams);

        return $this->render('update', ['transactions' => $transactions, 'dataProvider' => $dataProvider]);
    }

}
