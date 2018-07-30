<?php

use yii\helpers\Html;

$redirectUrl = Yii::$app->request->referrer ?: Yii::$app->homeUrl;
echo Html::a('<-'.Yii::t('common', 'Back'), $redirectUrl, ['class' => 'btn btn-link']);