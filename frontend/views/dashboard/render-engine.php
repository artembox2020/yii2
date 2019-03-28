<?php

use frontend\storages\MashineStatStorage;
use yii\widgets\Pjax;

/* @var $params array */
?>

<?php
    Pjax::begin(['enablePushState' => false]);
?>

<?php   
    echo Yii::$app->runAction(
        '/dashboard/render-action-builder',
        $params
    );
?>

<?php
    Pjax::end();
?>

<?php
    Pjax::begin(['enablePushState' => false]);
?>

<?php
    echo Yii::$app->runAction(
        '/dashboard/render-action-init'
    );
?>

<?php

    Pjax::end();
?>