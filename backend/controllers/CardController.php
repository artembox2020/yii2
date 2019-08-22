<?php

namespace backend\controllers;

use backend\models\search\TransactionSearch;
use frontend\models\Transactions;
use frontend\models\CustomerCards;
use Yii;
use backend\models\search\CardSearch;
use yii\web\Controller;

class CardController extends \frontend\controllers\Controller
{
    public function actionIndex()
    {
        $cards = new CardSearch();
        $dataProvider = $cards->search(Yii::$app->request->queryParams);

        if ($post = Yii::$app->request->post()) {
            $card = $this->getModel(['card_no' => $post['card_no']], new CustomerCards());
            Yii::$app->getSession()->setFlash(
                'updateMapData', Yii::$app->mapBuilder->updateMapDataFromPost($post, $card)
            );
        }

        return $this->render('index', ['cards' => $cards, 'dataProvider' => $dataProvider]);
    }

    public function actionUpdate($id)
    {
        $transactions = new TransactionSearch();
        $dataProvider = $transactions->search($id, Yii::$app->request->queryParams);

        return $this->render('update', ['transactions' => $transactions, 'dataProvider' => $dataProvider]);
    }

}
