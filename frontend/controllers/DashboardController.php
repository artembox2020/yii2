<?php

namespace frontend\controllers;

use common\models\User;
use DateTime;
use frontend\services\custom\Debugger;
use Yii;
use yii\web\Controller;
use frontend\storages\GoogleGraphStorage;
use frontend\storages\MashineStatStorage;
use frontend\storages\ModemStatStorage;
use frontend\services\globals\DateTimeHelper;
use frontend\models\Jlog;

/**
 * Class DashboardController
 * @package frontend\controllers
 */
class DashboardController extends Controller
{
    /**
     * Renders graph: all, green, grey, work, error WM mashines
     * Accepts data as post params
     * 
     * @return string
     */  
    public function actionAllGreenGreyWorkError()
    {
        $post = Yii::$app->request->post();
        list($start, $end, $action, $selector, $active) = [
            $post['start'], $post['end'], $post['action'], $post['selector'], $post['active']
        ];
        $ggs = new GoogleGraphStorage();
        $mss = new MashineStatStorage();
        $mss->setStepByTimestamps($start, $end);
        $options = ['colors' => ['cyan', 'green', 'grey', '#00ff7f', 'red']];
        $data = $mss->aggregateAllGreenGreyWorkErrorForGoogleGraphByTimestamps($start, $end, $options);
        $histogram = $ggs->drawHistogram($data, $selector);
        $actionBuilder = $this->actionRenderActionBuilder($start, $end, $action, $selector, $active);

        return $histogram.$actionBuilder;
    }

    /**
     * Renders graph: balance holders incomes
     * Accepts data as post params
     * 
     * @return string
     */  
    public function actionBalanceHolderIncomes()
    {
        $post = Yii::$app->request->post();
        list($start, $end, $action, $selector, $active) = [
            $post['start'], $post['end'], $post['action'], $post['selector'], $post['active']
        ];
        $ggs = new GoogleGraphStorage();
        $mss = new MashineStatStorage();

        $data = $mss->aggregateBalanceHoldersIncomesForGoogleGraph($start, $end, []);
        $histogram = $ggs->drawHistogram($data, $selector);
        $actionBuilder = $this->actionRenderActionBuilder($start, $end, $action, $selector, $active);

        return $histogram.$actionBuilder;
    }

    /**
     * Renders ajax submission form
     * 
     * @param int $start
     * @param int $end
     * @param string $action
     * @param string $selector
     * @param string $active
     * @param string $other
     * @param string $actionBuilder
     * 
     * @return string
     */  
    public function actionRenderAjaxFormSubmission(int $start, int $end, string $action, string $selector, string $active, string $other = null, string $actionBuilder = null)
    {

        return $this->renderAjaxFormSubmission($start, $end, $action, $selector, $active, $other, $actionBuilder);
    }

    /**
     * Renders data with timestamps by dropdown and date
     * 
     * @param string $active
     * @param string $date
     * @param string $other
     * 
     * @return string
     */    
    public function actionGetTimestampsByDropDown(string $active, string $date, $other = null)
    {
        $mss = new ModemStatStorage();

        return json_encode($mss->getTimeIntervalsByDropDown($active, $date, $other)); 
    }

    /**
     * Renders data with timestamps by dates between
     * 
     * @param string $active
     * @param string $dateStart
     * @param string $dateEnd
     * @param string $other
     * 
     * @return string
     */
    public function actionGetTimestampsByDatesBetween(string $active, string $dateStart, string $dateEnd, $other = null)
    {
        $mss = new ModemStatStorage();

        return json_encode($mss->getTimeIntervalsByDatesBetween($active, $dateStart, $dateEnd, $other)); 
    }

    /**
     * Renders action builder
     * 
     * @param int $start
     * @param int $end
     * @param string $action
     * @param string $selector
     * @param string $active
     * @param string $other
     * @param string $actionBuilder
     * 
     * @return string
     */
    public function actionRenderActionBuilder(
        int $start, int $end, string $action, string $selector, string $active,
        $other = null, $actionBuilder = 'builds/action-builder'
    )
    {
        $mss = new ModemStatStorage();
        $currentTimestamp = time() + Jlog::TYPE_TIME_OFFSET;
        global $randToken;
        $randToken = rand();

        return $this->renderPartial($actionBuilder, [
            'start' => $start,
            'end' => $end,
            'action' => $action,
            'selector' => $selector,
            'active' => $active,
            'other' => $other,
            'actionBuilder' => $actionBuilder,
            'controller' => $this,
            'model' => $mss,
            'currentTimestamp' => $currentTimestamp,
            'random' => $randToken
        ]);
    }

    /**
     * Renders submission form
     * 
     * @param int $start
     * @param int $end
     * @param string $action
     * @param string $selector
     * @param string $active
     * @param string $other
     * @param string $actionBuilder
     * 
     * @return string
     */
    public function renderAjaxFormSubmission(
        int $start, int $end, string $action, string $selector, string $active, 
        string $other = null, string $actionBuilder = null
    )
    {

        return Yii::$app->view->render('/dashboard/forms/submission', [
            'start' => $start,
            'end' => $end,
            'action' => $action,
            'selector' => $selector,
            'active' => $active,
            'other' => $other,
            'actionBuilder' => $actionBuilder
        ]);
    }

    /**
     * Renders graph builder
     *
     * @return string
     */
    public function renderGraphBuilder()
    {
        global $isGraphBuilder;

        if (!$isGraphBuilder) {
            $isGraphBuilder = true;

            return Yii::$app->view->render('/dashboard/graph-builder', []);
        }
    }

    /**
     * Renders init action
     * 
     * @return string
     */
    public function actionRenderActionInit()
    {
        global $randToken;
        $params['random'] = $randToken;
        $ggs = new GoogleGraphStorage();

        return $this->renderPartial('inits/action-init', $params);
    }

    /**
     * Base action, renders appropriate action
     * 
     * @param string $selector
     * @param string $action
     * @param string $active
     * @param string $other
     * @param string $actionBuilder
     * 
     * @return string
     */
    public function actionRenderEngine(string $selector, string $action, string $active, string $other = null, string $actionBuilder = null)
    {
        $mss = new ModemStatStorage();
        $params = $mss->getInitialParams($selector, $action, $active);
        $params['other'] = $other;
        $params['actionBuilder'] = $actionBuilder ?? 'builds/action-builder';

        return $this->renderPartial('render-engine', [
            'params' => $params
        ]);
    }

    /**
     * Renders modem level signal statistics
     * Accepts data as post params
     * 
     * @return string
     */  
    public function actionModemLevelSignal()
    {
        $post = Yii::$app->request->post();
        list($start, $end, $action, $selector, $active, $other, $actionBuilder) = [
            $post['start'], $post['end'], $post['action'], $post['selector'], $post['active'],
            $post['other'], $post['actionBuilder']
        ];

        $ggs = new GoogleGraphStorage();
        $mss = new ModemStatStorage();
        $options = [
            'colors' => [
                'red',
                'green'
            ]
        ];

        if ($active == 'current day') {
            $data = $mss->aggregateCurrentModemLevelSignalsForGoogleGraph($other, $options);
        } else {
            $data = $mss->aggregateModemLevelSignalsForGoogleGraph($start, $end, $other, $options);
        }

        $histogram = $ggs->drawLine($data, $selector);
        $actionBuilder = $this->actionRenderActionBuilder($start, $end, $action, $selector, $active, $other, $actionBuilder);

        return $histogram.$actionBuilder;
    }
}