<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
$menu = [];
$machine_menu = [];
?>
<b>
    <?= $this->render('/net-manager/_sub_menu', [
        'menu' => $menu,
    ]) ?>
</b>
<h1><?= Html::encode($this->title) ?></h1>

<b>
    <?= $this->render('/net-manager/_machine_menu', [
            'machine_menu' => $machine_menu,
    ]) ?>
</b>
<br><br>
<p>
    <?= Html::a(Yii::t('frontend', 'Add WM Machine'), ['/net-manager/wm-machine-add'], ['class' => 'btn btn-success']) ?>
    <?= Html::a(Yii::t('frontend', 'Add GD Machine'), ['/gd-mashine/create'], ['class' => 'btn btn-success']) ?>
</p>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
//    'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        ['attribute' => 'serial_number',
            'label' => Yii::t('frontend', 'Serial number'),
        ],
        ['attribute' => 'number_device',
            'label' => Yii::t('frontend', 'Device number'),
            'value' => function ($dataProvider) {
                return Html::a(Html::encode($dataProvider->number_device), Url::to(['wm-machine-view', 'id' => $dataProvider->id]));
            },
            'format' => 'raw',],
//        ['attribute' => 'type_mashine',
//            'label' => Yii::t('frontend', 'Type mashine'),
//            'value' => function ($dataProvider) {
//                return Html::a(Html::encode($dataProvider->type_mashine), Url::to(['wm-machine-view', 'id' => $dataProvider->id]));
//            },
//            'format' => 'raw',],
        'model',
//        ['attribute' => 'created_at',
//            'label' => Yii::t('frontend', 'Date Install'),
//            'format' => ['date', 'php:d/m/Y']
//        ],
        ['attribute' => 'address.address',
            'label' => Yii::t('frontend', 'Address Install'),
        ],
        [
            'attribute' => 'balanceHolder.name',
            'label' => Yii::t('frontend', 'Balance Holder'),
        ],
        ['attribute' => 'updated_at',
            'label' => Yii::t('frontend', 'Last ping'),
            'value' => function($dataProvider) {
                return date('[H:i:s] d.m.Y', $dataProvider->updated_at);
            },
        ],

    ]
]);
?>

<p><u><b><?= Yii::t('frontend','Consolidated technical data') ?></b></u><p/>
<p><u><b><?= Yii::t('frontend','Consolidated financial data') ?></b></u><p/>
