<?php

namespace frontend\controllers;

use frontend\models\CbEncashment;
use frontend\models\CbLogSearch;
use frontend\models\CbLogSearchFilter;
use frontend\models\Jlog;
use frontend\services\custom\Debugger;
use frontend\services\globals\EntityHelper;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use frontend\models\ImeiDataSearch;
use Mpdf\Mpdf;

/**
 * Class EncashmentJournalController
 * @package frontend\controllers
 */
class EncashmentJournalController extends Controller
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * main action
     * 
     * @param array $params
     * @return string
     */
    public function actionIndex(array $params = [])
    {
        if (!\Yii::$app->user->can('viewFinData', ['class'=>static::class])) {
            \Yii::$app->getSession()->setFlash('AccessDenied', 'Access denied');
            return $this->render('@app/modules/account/views/denied/access-denied');
        }

        if (!empty($params['isEncashment'])) {

            return $this->actionEncashment($params);
        }

        return Yii::$app->runAction('journal/index', ['isEncashment' => true]);
    }

    /**
     * encashment journal action
     * @param array $prms
     * @return string
     */
    public function actionEncashment($prms = [])
    {
        if (!\Yii::$app->user->can('viewFinData', ['class'=>static::class])) {
            \Yii::$app->getSession()->setFlash('AccessDenied', 'Access denied');
            return $this->render('@app/modules/account/views/denied/access-denied');
        }

        $searchModel = new CbLogSearch();
        $searchFilter = new CbLogSearchFilter();
        $entityHelper = new EntityHelper();

        $params = $entityHelper->makeParamsFromRequest(
            [
                'type_packet', 'imei', 'address', 'id', 'selectionName', 'selectionCaretPos',
                'wm_mashine_number',
                'filterCondition' => [
                    'date', 'type_packet', 'address', 'imei', 'id',
                    'number', 'unix_time_offset'
                ],
                'val1' => [
                    'date', 'type_packet', 'address', 'imei', 'id',
                    'number', 'unix_time_offset'
                ],
                'val2' => [
                    'date', 'type_packet', 'address', 'imei', 'id',
                    'number', 'unix_time_offset'
                ],
                'inputValue' => [
                    'date', 'type_packet', 'address', 'imei', 'id',
                    'number', 'unix_time_offset'
                ],
                'sort', 'page_size',
                'CbLogSearch' => [
                    'from_date', 'to_date',
                    'inputValue' => ['date', 'unix_time_offset'],
                    'val2' => ['date', 'unix_time_offset']
                ]
            ]
        );
        $params['type_packet'] = Jlog::TYPE_PACKET_ENCASHMENT;

        $params = $searchModel->setParams($searchModel, $params, $prms);
        $dataProvider = $searchModel->searchEncashment($params);
        $dateFieldName = $searchFilter->getDateFieldNameByParams($params);

        $searchModel->inputValue[$dateFieldName] = $params['inputValue'][$dateFieldName];
        $searchModel->val2[$dateFieldName] = $params['val2'][$dateFieldName];
        $recountAmountScript = $this->getRecountAmountScript();
        $model = ['banknote_face_values' => '1-0'];
        $nominalsView = $searchModel->getNominalsView($model);
        $script = $this->getScript(['nominalsView' => $nominalsView]);

        $eventSelectors = [
            'change' =>
                '.journal-filter-form select,'.
                '.journal-filter-form input#mashine-from-date,'.
                '.journal-filter-form input#mashine-to-date'
        ];

        $submitFormOnInputEvents = $entityHelper->submitFormOnInputEvents('.journal-filter-form', $eventSelectors);
        $removeRedundantGrids = $entityHelper->removeRedundantGrids('.journal-grid-view');

        return $this->renderPartial('index', [
            'searchModel' => $searchModel,
            'searchFilter' => $searchFilter,
            'dataProvider' => $dataProvider,
            'params' => $params,
            'recountAmountScript' => $recountAmountScript,
            'script' => $script,
            'submitFormOnInputEvents' => $submitFormOnInputEvents,
            'removeRedundantGrids' => $removeRedundantGrids
        ]);
    }

    /**
     * Updates `recount_amount` field of cb_encashment table 
     * @param int $logId
     * @param int $value
     */
    public function actionUpdateRecountAmount($logId, $value)
    {
        $searchModel = new CbEncashment();
        $searchModel->updateRecountAmount($logId, $value);
    }

    /**
     * Returns recount amount script
     *
     * @return string
     */
    public function getRecountAmountScript()
    {

        return Yii::$app->view->render(
            "/encashment-journal/recount_amount",
            []
        );
    }

    /**
     * Returns main script
     *
     * @param array $scriptParams
     * @return string
     */
    public function getScript($scriptParams)
    {

        return Yii::$app->view->render(
            "/encashment-journal/script",
            $scriptParams
        );
    }

    /**
     * Print action
     */
    public function actionPrint()
    {
        $post = Yii::$app->request->post();
        $filename = $post['filename'];
        $caption = $post['caption'];
        $title = $post['title'];
        $html = $post['html'];
        $this->printEncashmentSummary($filename, $caption, $title, $html);
    }

    /**
     * Prints encashment summary and stores it as .pdf file
     *
     * @param string $filename
     * @param string $caption
     * @param string $title
     * @param string $html
     * @return string
     */
    public function printEncashmentSummary($filename, $caption, $title, $html)
    {
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'orientation' => 'P',
            'tempDir' => Yii::getAlias("@frontend")."/../storage/temp"
        ]);

        $mpdf->setTitle($title);

        $style = file_get_contents('static/css/style.css');

        $encashmentStyle = file_get_contents('static/css/encashment-print-style.css');

        $mpdf->WriteHTML($style, \Mpdf\HTMLParserMode::HEADER_CSS);
        $mpdf->WriteHTML($encashmentStyle, \Mpdf\HTMLParserMode::HEADER_CSS);

        $mpdf->WriteHTML($caption);
        $mpdf->WriteHTML($html);
        $mpdf->Output($filename.".pdf", \Mpdf\Output\Destination::DOWNLOAD);
    }
}
