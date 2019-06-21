<?php

namespace api\modules\v1\controllers;

use api\modules\v1\UseCase\Terminal\Terminal;
use Yii;
use yii\web\Controller;

/**
 * Class ImeiController
 * @package api\modules\v1\controllers
 */
class TerminalController extends Controller
{

    /**
     * @param string $address_name
     * @return \yii\web\Response
     * @throws \yii\db\Exception
     */
    public function actionIndex(string $address_name)
    {
        $service = new Terminal();
        $result = $service->getStaff($address_name);

//        return $this->render('index', [
//            'result' => $result
//        ]);

        return $this->asJson($result);
    }
}
