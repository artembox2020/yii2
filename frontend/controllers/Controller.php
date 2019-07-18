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
