<?php
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

//\frontend\services\custom\Debugger::dd($model);
?>

<div class="news-item">
    <h2><?= Html::encode($model['name']) ?></h2>
    <?= HtmlPurifier::process($model['name']) ?>
    <p><?= Html::a('Delete', ['delete', 'id' => $model['id']], ['class' => 'btn btn-success', 'data-method' => 'POST']) ?></p>
</div>​
