<?php

namespace frontend\controllers;

use common\models\User;
use frontend\models\ImeiDataSearch;
use frontend\models\ImeiAction;
use frontend\models\WmMashineDataSearch;
use frontend\models\WmMashine;
use frontend\models\Jlog;
use frontend\services\globals\Entity;
use frontend\services\globals\EntityHelper;
use Yii;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use yii\helpers\ArrayHelper;

class Controller extends \yii\web\Controller
{
    /**
     * Gets path depending on layout
     * 
     * @param string $view
     * @param bool $ignoreLayout
     * 
     * @return string
     */
    public function getPath($view, $ignoreLayout = false)
    {
        $layout = Yii::$app->layout;
        $layoutParts = explode("-", $layout);

        if ($ignoreLayout || count($layoutParts) < 2) {

            return $view;
        }

        return $view.'-'.$layoutParts[1];
    }

    /**
     * Gets model instance according to filter data
     * 
     * @param array $data
     * @param instance $instance
     * 
     * @return instance
     * @throws \yii\web\NotFoundHttpException
     */
    public function getModel($data, $instance)
    {
        $entity = new Entity();

        if (!Yii::$app->user->can('super_administrator')) {
            $data['company_id'] = $entity->getCompanyId();
        }

        $model = $instance::find()->andWhere($data)->limit(1)->one();

        if (!$model) {
            throw new \yii\web\NotFoundHttpException(Yii::t('common','Entity not found'));
        }

        return $model;
    }

    /**
     * Gets user roles available
     * 
     * @return array
     */
    public function getRoles()
    {
        $roles = ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'name');

        $now = Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId());

        unset($roles[array_search('super_administrator', $roles)]);
        unset($roles[array_search('user', $roles)]);

        foreach ($roles as $key => $role) {
            $roles[$key] = Yii::t('backend', $role);
        }

        return $roles;
    }
    
     /**
     * Performs card operations by card post data (block/unblock + refill)
     
     * @param array $post
     * @param \frontend\models\CustomerCards $card
     * 
     * @return string|bool
     */
    public function updateMapData($post, $card)
    {
        $model = Yii::$app->mapBuilder->getUpdateMapDataModelFromPost($post, $card);

        Yii::$app->session->set(
            'update-map-data-status',
            Yii::$app->mapBuilder->getFlashMessageByStatus(
                $model ? $model->status : Yii::$app->mapBuilder::STATUS_ERROR
            )
        );

        // in case of need payment confirmation redirect to liqpay payment page
        if ($model->status == Yii::$app->mapBuilder::STATUS_PENDING_CONFIRMATION) {

            return $this->render(
                '@frontend/views/map/confirm_payment',
                [
                    'payment_button' => Yii::$app->mapBuilder->createOrderAndPaymentButton(
                        $model, env('SERVER_URL'), env('FRONTEND_URL').Yii::$app->request->url
                    )
                ]
            );
        }

        return false;
    }

    /*public function render($view, $params = [])
    {
        $view = $this->getPath($view);

        return parent::render($view, $params);
    }
    
    public function renderPartial($view, $params = [])
    {
        $view = $this->getPath($view);

        return parent::renderPartial($view, $params);
    }*/
}
