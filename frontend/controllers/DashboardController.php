<?php

namespace frontend\controllers;

use common\models\User;
use DateTime;
use frontend\services\custom\Debugger;
use phpDocumentor\Reflection\Types\Integer;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\Json;
use yii\web\Controller;
use frontend\models\ContactForm;
use frontend\models\Base;
use frontend\models\Devices;
use frontend\models\ImeiDataSummarySearch;
use frontend\models\Zlog;
use frontend\models\Com;
use frontend\models\Org;
use common\models\UserSearch;
use vova07\fileapi\actions\UploadAction as FileAPIUpload;
use frontend\storages\GoogleGraphStorage;
use frontend\storages\MashineStatStorage;

/**
 * Class DashboardController.
 */
class DashboardController extends Controller
{
    const STEP = 3600 * 24;

    public function actionActiveWorkError($start, $end, $selector)
    {
        //list($start, $end) = $this->getTimeIntervals(Yii::$app->request->get());
        $ggs = new GoogleGraphStorage();
        $mss = new MashineStatStorage();
        $data = $mss->aggregateActiveWorkErrorForGoogleGraphByTimestamps($start, $end, self::STEP);

        return $ggs->drawHistogram($data, $selector);
    }

    public function getTimeIntervals($get)
    {

        return [$get['start'], $get['end']];
    }
}
