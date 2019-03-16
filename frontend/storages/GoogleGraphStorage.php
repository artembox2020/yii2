<?php

namespace frontend\storages;
use Yii;

class GoogleGraphStorage implements GraphStorageInterface
{
    public $storagePath = '@frontend/storages/GoogleGraphStorage';

    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        global $initializationIndicator;

        if (empty($initializationIndicator)) {

            Yii::$app->view->registerJsFile(
                'https://www.gstatic.com/charts/loader.js',
                ['position' => \yii\web\View::POS_HEAD]
            );

            $initializationIndicator = true;
        }
    }
    
    public function drawHistogram(array $data, string $selector)
    {
        echo Yii::$app->view->render($this->storagePath.'/drawHistogram', ['storage' => $this, 'data' => $data, 'selector' => $selector]);
    }
    
}