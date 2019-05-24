<?php

namespace frontend\storages;
use Yii;

/**
 * Class GoogleGraphStorage
 * @package frontend\storages;
 */
class GoogleGraphStorage implements GraphStorageInterface
{
    public $storagePath = '@frontend/storages/GoogleGraphStorage';

    /**
     * @inheritdoc
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     * Includes lib files if necessary 
     */
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

    /**
     * Draws histogram by input data, inside container by selector
     * 
     * @param array $data
     * @param string $selector
     */ 
    public function drawHistogram(array $data, string $selector)
    {
        return Yii::$app->view->render($this->storagePath.'/drawHistogram', ['storage' => $this, 'data' => $data, 'selector' => $selector]);
    }

    /**
     * Draws line by input data, inside container by selector
     * 
     * @param array $data
     * @param string $selector
     * @param int $start
     * @param int $end
     * 
     * @return string
     */ 
    public function drawLine(array $data, string $selector, int $start, int $end)
    {
        if ($start == $end) {
            $end = $start + 3600 * 24;
        }

        return Yii::$app->view->render(
            $this->storagePath.'/drawLine',
            ['storage' => $this, 'data' => $data, 'selector' => $selector, 'start' => $start, 'end' => $end]
        );
    }
}