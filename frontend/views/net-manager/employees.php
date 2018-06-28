<?php
/* @var $this yii\web\View */
/* @var $model frontend\models\Company */
/* @var $users common\models\User */
/* @var $balanceHolders */
/* @var $addressBalanceHolders */
/* @var $users */
/* @var $profile common\models\UserProfile */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\User;
?>
<?php $menu = []; ?>
<b>
<?= $this->render('_sub_menu', [
        'menu' => $menu,
    ]) ?>
</b>
<br/>
<div>
    <?= Html::a("[".Yii::t('frontend', 'Create user')."]", ['account/default/create'], ['class' => 'btn btn-success', 'style' => 'color: #fff;']) ?>
</div>
<div class="account-default-users">
    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= yii\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'id',
                'label' => Yii::t('common', 'Number'),
                'value' => function($model, $key, $index) {
                    if(empty($_GET['per-page'])) $perPage = 10; else $perPage = $_GET['per-page'];
                    if(!empty($_GET['page'])) return ($_GET['page'] - 1) * $perPage + $index + 1;
                    else return $index + 1;
                },
                'filter' => false
            ],
            
            [
                'attribute' => 'flp',
                'label' => Yii::t('common', 'Fistname Lastname Patronymic'),
                'value' => function($data) {
                    return $data->userProfile->firstname." ".$data->userProfile->lastname;
                },
            ],
            
            [
                'attribute' => 'position',
                'label' => Yii::t('common', 'Position'),
                'value' => function($data) {
                    return $data->userProfile->position;
                },
            ],
 
            [
                'label' => Yii::t('common', 'Server Rights'),
                'value' => function($data) {
                    return  $data->getUserRoleName($data->id);
                },
            ],
            
            [
                'attribute' => 'status',
                'label' => Yii::t('common', 'Status'),
                'value' => function($data) {
                    switch($data->status) {
                        case User::STATUS_INACTIVE: return Yii::t('common','Inactive');
                        case User::STATUS_ACTIVE: return Yii::t('common','Active');
                        default: return Yii::t('common','Undefined');
                    }
                },
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'status',
                    [
                        User::STATUS_ACTIVE => Yii::t('common','Active'),    
                        User::STATUS_INACTIVE => Yii::t('common','Inactive'),
                    ],
                    ['class' => 'form-control', 'prompt' => Yii::t('common','All')]
                ),
            ],
 
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t('common', 'Actions'),
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'update' => function ($url,$model) {
                        return Html::a('<span class="glyphicon glyphicon-screenshot"></span>', ['edit-employee', 'id' =>$model->id]);
                    },
                    
                    'view' => function ($url,$model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['view-employee', 'id' =>$model->id]);
                    },
                    
                    'delete' => function($url, $model) {
                        if($model->is_deleted) return '';
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete-employee', 'id' => $model->id], 
                        [
                            'class' => '',
                            'data' => [
                                'confirm' => Yii::t('common', 'Delete Confirmation'),
                                'method' => 'post',
                            ],
                        ]);
                    }
                ],
            ],
        ],
    ]);  ?>
</div>
