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
use yii\widgets\DetailView;
use common\models\User;

?>
<?php $menu = []; ?>
<b>
<?= $this->render('_sub_menu', [
        'menu' => $menu,
    ]) ?>
</b>
<br/><br/>
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
                'attribute' => 'name',
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
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t('common', 'Actions'),
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'update' => function ($url,$model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['edit-employee', 'id' =>$model->id]);
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

<p><u><b><?= Yii::t('frontend','Summary Technical Data') ?></b></u><p/>

<?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label' => Yii::t('frontend', 'Count Employee'),
                'value' => $model->getUserCount()
            ],
            [
                'label' => Yii::t('frontend', 'Count Administrative Employee'),
                'value' => $model->getUserCountByRoles([User::ROLE_ADMINISTRATOR])
            ],
            [
                'label' => Yii::t('frontend', 'Count Technical Employee'),
                'value' => $model->getUserCountByRoles([User::ROLE_TECHNICIAN])
            ],
            [
                'label' => Yii::t('frontend', 'Count Financier Employee'),
                'value' => $model->getUserCountByRoles([User::ROLE_FINANCIER])
            ],
            [
                'label' => Yii::t('frontend', 'Count Other Employee'),
                'value' => $model->getUserOtherCount()
            ],
        ]
    ]);
?>
