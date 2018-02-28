<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
/* @var $this yii\web\View */
/* @var $model frontend\models\Company */

$this->title = $model->name;
//$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend', 'Companies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('frontend', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('frontend', 'Add Employee'), ['add-employee', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
<!--         Html::a(Yii::t('frontend', 'Delete'), ['delete', 'id' => $model->id], [-->
<!--            'class' => 'btn btn-danger',-->
<!--            'data' => [-->
<!--                'confirm' => Yii::t('frontend', 'Are you sure you want to delete this item?'),-->
<!--                'method' => 'post',-->
<!--            ],-->
<!--        ]) -->
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            [
                'attribute' => 'img',
                'format' => 'html',
                'value' => function ($data) {
                    return Html::img(Yii::getAlias('@storageUrl/logos/'. $data['img'],['max-width' => '80px']));
                },
            ],
            'description:ntext',
            'website',
        ],
    ]) ?>
    <b>Юзеры комании:</b>
    <p>
        <?php foreach ($users as $user) : ?>
            <?= $user->username; ?> <br>
        <?php endforeach; ?>
    </p>
</div>
