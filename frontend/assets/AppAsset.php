<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'static/css/style.css',
        'static/css/journal-filters/style.css',
    ];
    public $js = [
        'static/js/journal-filters/main.js',
        //'https://www.gstatic.com/charts/loader.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'common\assets\OpenSans',
        'common\assets\FontAwesome',
    ];
}
