<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAssetNew extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'static/css/style.css',
        'static/css/journal-filters/style.css',
        'https://use.fontawesome.com/releases/v5.8.1/css/all.css',
        'https://fonts.googleapis.com/css?family=PT+Sans:400,400i,700,700i&amp;amp;subset=cyrillic-ext',
        'https://fonts.googleapis.com/css?family=PT+Sans&display=swap',
        'static/css/monitoringdata/style.css',
        'static/css/style-new.css'
    ];

    public $js = [
        'static/js/journal-filters/main.js',
    ];

    public $jsOptions = [
        'position' => \yii\web\View::POS_END
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'common\assets\OpenSans',
        'common\assets\FontAwesome',
    ];
}
