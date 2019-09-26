<?php

use frontend\components\responsive\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel frontend\modules\account\models\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('frontend', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="account-default-users">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('frontend', 'Create user'), ['create'], ['class' => 'btn btn-success']) ?>
<!-- Html::a(Yii::t('frontend', 'Roles'), ['/rbac/access/role'], ['class' => 'btn btn-success']) -->
<!-- Html::a(Yii::t('frontend', 'Permissions'), ['/rbac/access/permission'], ['class' => 'btn btn-success'])-->
    </p>


    <?= $this->render('_search', ['model' => $searchModel]) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'username',
                'format' => 'html',
                'value' => function ($model) {
                    return Html::a(Html::encode($model['username']), ['view', 'id' => $model['id']]);
                }
            ],
            [
                'attribute' => 'Role',
                'format' => 'html',
                'value' => function ($model) {
                    return $model->getUserRoleName($model->id);
                }
            ],
            'created_at:datetime',
            'action_at:datetime',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
            ],
        ],
        'summary' => false,
    ]) ?>
</div>
