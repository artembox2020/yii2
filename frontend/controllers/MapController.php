<?php

namespace frontend\controllers;

use common\models\User;
use frontend\models\ImeiDataSearch;
use frontend\models\ImeiAction;
use frontend\models\WmMashineDataSearch;
use frontend\models\WmMashine;
use frontend\models\Jlog;
use frontend\services\globals\EntityHelper;
use Yii;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use backend\models\search\CardSearch;

/**
 * customer cards processing
 * Class MapController
 * @package frontend\controllers
 */
class MapController extends \frontend\controllers\Controller
{
    /**
     * Gets cards and users list
     * 
     * @return string
     */
    public function actionIndex()
    {
        $cards = new CardSearch();
        $dataProvider = $cards->search(Yii::$app->request->queryParams);

        return $this->render(
            'index',
            [
                'cards' => $cards,
                'dataProvider' => $dataProvider,
                'action' => $this->action->id
            ]
        );
    }
    
    /**
     * Gets cards and users list
     * 
     * @return string
     */
    public function actionUser()
    {
        $cards = new CardSearch();
        $dataProvider = $cards->searchUser(Yii::$app->request->queryParams);

        return $this->render(
            'user',
            [
                'cards' => $cards,
                'dataProvider' => $dataProvider,
                'action' => $this->action->id
            ]
        );
    }
}
