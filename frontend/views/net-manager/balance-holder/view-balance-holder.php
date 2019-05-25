<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
use yii\grid\GridView;
use frontend\services\custom\Debugger;
use frontend\controllers\OtherContactPersonController;
/* @var $this yii\web\View */
/* @var $model frontend\models\Company */
/* @var $users common\models\User */
/* @var $balanceHolders  */
?>
<?php 
    $menu = [];
    $dateFormat = "d.m.Y";
?>
<b>
    <?= $this->render('/net-manager/_sub_menu', [
        'menu' => $menu,
    ]) ?>
</b><br><br>
<p><u><b><?= Yii::t('frontend','Balance Holder') ?></b></u><p/>
<br/>
<div>
    <?= Html::a(
            '['.Yii::t('frontend', 'Update Balance Holder').']',
            
            ['/balance-holder/update', 'id' => $model->id],
            
            ['class' => 'btn btn-success', 'style' => 'color: #fff;']
        );
    ?>
    
    <?= Html::a(
            '['.Yii::t('frontend', 'Delete Balance Holder').']',
            
            ['/balance-holder/delete', 'id' => $model->id],
            
            [
                'class' => 'btn btn-success',
                'style' => 'color: #fff;',
                'data' => [
                    'confirm' => Yii::t('common', 'Delete Confirmation'),
                    'method' => 'post',
                ],
            ]
        );
    ?>                    
</div>
<br/>
<?php
    $person = $dataProvider->query->one();
    $dataProvider->query = $dataProvider->query->offset(1);
    
    $contactPerson = 
        [
            'label' => Yii::t('frontend','Contact Person'),
            'format' => 'raw',
            'value' => (!empty($person->name) ? $person->name : '')
                ." ".
                (
                    count($model->otherContactPerson) <= OtherContactPersonController::NINE_DIGIT 
                    ? OtherContactPersonController::getCreateLink() : ''
                )
        ];
        
    if(!empty($person->id)) {
        $contactPersonPosition =
            [
                'label' => Yii::t('common','Position'),
                'value' => $person->position
            ];
            
        $contactPersonPhone =
            [
                'label' => Yii::t('frontend','Phone'),
                'value' => $person->phone
            ];;
            
        $contactPersonCreated =
            [
                'label' => Yii::t('frontend','Created'),
                'value' => date($dateFormat, $person->created_at)
            ];
            
        $contactPersonControls =
            [
                'label' => Yii::t('common','Actions'),
                'format' => 'raw',
                'value' => OtherContactPersonController::getUpdateLink($person->id)." ".OtherContactPersonController::getDeleteLink($person->id)
            ];    
    }
    else {
        $contactPersonPosition = [];
        $contactPersonPhone = [];
        $contactPersonCreated = [];
        $contactPersonControls = [];
    }
    
    $widgetAttributes = [
            [
                'label' => Yii::t('common', 'Logo'),
                'format' => 'raw',
                'value' => Html::img('@web/storage/logos/' . $model->img)
            ],
            [
                'label' => Yii::t('common','Name'),
                'value' => $model->name
            ],
            [
                'label' => Yii::t('frontend','Address'),
                'value' => $model->address
            ],
            [
                'label' => Yii::t('frontend','Date Start'),
                'value' => date($dateFormat, $model->date_start_cooperation)
            ],
            [
                'label' => Yii::t('frontend','Date Monitoring'),
                'value' => date($dateFormat, $model->date_connection_monitoring)
            ],
            $contactPerson,
            $contactPersonPosition,
            $contactPersonPhone,
            $contactPersonCreated,
            $contactPersonControls
        ];
        
    $widgetAttributes= array_filter($widgetAttributes, function($value) { return ( is_array($value) && count($value) > 0 ); });
    
?>

<?= DetailView::widget([
        'model' => $model,
        'attributes' => $widgetAttributes
    ]);
?>

<p><u><b><?= Yii::t('frontend','Address List') ?></b></u><p/>

<?php echo Yii::$app->runAction('/net-manager/addresses', ['balanceHolderId' => $model->id]); ?>

<p><u><b><?= Yii::t('frontend','Summary Technical Data') ?></b></u><p/>

<?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label' =>  Yii::t('frontend', 'Count Addresses'),
                'value' => $model->countAddresses
            ],
            [
                'label' =>  Yii::t('frontend', 'Count Imeis'),
                'value' => $model->countWashpay
            ],
            [
                'label' =>  Yii::t('frontend', 'Count Wash Machine'),
                'value' => $model->countWmMachine
            ],
            [
                'label' =>  Yii::t('frontend', 'Count Gd Machine'),
                'value' => $model->countGdMachine
            ],
            [
                'label' => Yii::t('frontend', 'Last errors'),
                'value' => Yii::t('frontend', 'Last errors'),
            ],
            [
                'label' => Yii::t('frontend', 'Last repairs'),
                'value' => Yii::t('frontend', 'Last repairs'),
            ]
        ]
    ]);
?>

<div><b><u><?= Yii::t('frontend','Consolidated Financial Data') ?></u></b></div>

<?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label' =>  Yii::t('frontend', 'Money Amount'),
                'value' => 1000
            ],
            [
                'label' =>  Yii::t('frontend', 'Average Day Income'),
                'value' => 200
            ],
            [
                'label' =>  Yii::t('frontend', 'Average Income For 1WM'),
                'value' => 400
            ],
            [
                'label' =>  Yii::t('frontend', 'Average Income for 1 Point WM'),
                'value' => 480
            ],
        ]
    ]);
?>


<div><b><u><?= Yii::t('frontend','Other Contact People') ?></u></b></div>
<br>
<p>
    <?= OtherContactPersonController::getCreateLink() ?>
</p>
<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'name',
            'position',
            'phone',
            [
                'label' => Yii::t('common','Actions'),
                'format' => 'raw',
                'value' => function($model) {
                    return OtherContactPersonController::getUpdateLink($model->id)." ".OtherContactPersonController::getDeleteLink($model->id);
                }
            ]
        ]
]); ?>