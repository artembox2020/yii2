<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use frontend\services\custom\Debugger;
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
            'label' => Yii::t('frontend', 'Inventory number'),
            'value' => function ($dataProvider) {
                return Html::a(Html::encode($dataProvider->inventory_number), Url::to(['wm-machine-view', 'id' => $dataProvider->id]));
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
                return date('[H:i:s] d.m.Y', $dataProvider->ping);
            },
        ],
        'buttons'=>[
            'label' => Yii::t('frontend', 'View'),
            'format' => 'raw',
            'options'=>['class' => 'btn btn-primary'],
            'value' => function ($dataProvider) {
                return Html::a(Html::encode($dataProvider->id), Url::to(['wm-machine-view', 'id' => $dataProvider->id]));
            },
        ],
    ]
]);
?>

<p><u><b><?= Yii::t('frontend','Consolidated technical data') ?></b></u><p/>

<!-- Summary by models -->
<?php ob_start(); ?>
<?= GridView::widget([
    'dataProvider' => $provider,
    'columns' => [
        ['attribute' => 'model',
            'label' => Yii::t('frontend', 'By Models'),
            'value' => 'model',
        ],
        [
            'label' => Yii::t('frontend', 'General Count'),
            'value' => function ($provider) {
                return $provider->getModelNameCount($provider->model);
            },
        ],
    ]
]);
?>
<?php $modelWm = ob_get_clean(); ?>

<?php ob_start(); ?>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'value' => function ($dataProvider) {
    foreach ($dataProvider->getWmAll() as $val) {
        $diff = $dataProvider->getUpTo1year($dataProvider->date_build);
        if ($diff <= 1 and $diff < 2) {
            $one[] = $diff;
            return 'До '. $dataProvider->getUpTo1year($dataProvider->date_build) .' року - ' . count($one);
        }
        $one[] = $diff;
        if ($diff >= 2 and $diff < 3) {
            $one[] = $diff;
            return 'До '. $dataProvider->getUpTo1year($dataProvider->date_build) .' року - ' . count($one);
        }
        if ($diff >= 3 and $diff < 4) {
            $one[] = $diff;
            return 'До '. $dataProvider->getUpTo1year($dataProvider->date_build) .' року - ' . count($one);
        }
        if ($diff >= 4 and $diff < 5) {
            $one[] = $diff;
            return 'До '. $dataProvider->getUpTo1year($dataProvider->date_build) .' року - ' . count($one);
        }
        if ($diff >= 5 and $diff < 5) {
            $one[] = $diff;
            return 'До '. $dataProvider->getUpTo1year($dataProvider->date_build) .' року - ' . count($one);
        }
        if ($diff > 5) {
            $one[] = $diff;
//            return 'Старше за 5 років - ' . count($six);
        }
    }
//                if
                return 'До '. $diff .' року - ' . $diff;
            }
        ],
        [
            'label' => Yii::t('frontend', 'До 1 року'),
            'value' => function ($dataProvider) {
                return $dataProvider->getUpTo1year($dataProvider->date_build);
            }
        ],
//        [
//            'label' => Yii::t('frontend', 'До 2х років'),
//            'value' => function ($dataProvider) {
//                return $dataProvider->getUpTo1year($dataProvider->date_build);
//            }
//        ],
//        [
//            'label' => Yii::t('frontend', 'До 3х років'),
//            'value' => function ($dataProvider) {
//                return $dataProvider->getUpTo1year($dataProvider->date_build);
//            }
//        ],
//        [
//            'label' => Yii::t('frontend', 'До 4х років'),
//            'value' => ''
//        ],
//        [
//            'label' => Yii::t('frontend', 'До 5х років'),
//            'value' => ''
//        ],
//        [
//            'label' => Yii::t('frontend', 'Старше за 5 років'),
//            'value' => ''
//        ],
//        ['attribute' => 'model',
//            'label' => Yii::t('frontend', 'До 1 року'),
//            'value' => 'model',
//        ],
//        ['attribute' => 'model',
//            'label' => Yii::t('frontend', 'До 2х років'),
//            'value' => 'model',
//        ],
//        [
//            'label' => Yii::t('frontend', 'General Count'),
//            'value' => function ($model) {
//                return $model->getByYearProduction($model->date_build);
//            },
//        ],
    ]
]);
?>
<?php $byYearProd = ob_get_clean(); ?>

<!-- Main Detail View -->
<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        [
            'label' => Yii::t('frontend', 'General Count'),
            'value' => $model->getGeneralCount()
        ],
        [
            'label' =>  Yii::t('frontend', 'By Models'),
            'format' => 'raw',
            'value' => $modelWm
        ],
        [
            'label' =>  Yii::t('frontend', 'By year of production'),
            'format' => 'raw',
            'value' => $byYearProd
        ],
        [
            'label' =>  Yii::t('frontend', 'Status'),
            'format' => 'raw',
            'value' => ''
        ],
        [
            'label' =>  Yii::t('frontend', 'By Location'),
            'format' => 'raw',
            'value' => ''
        ],
    ]
]);
?>
<p><u><b><?= Yii::t('frontend','General Info') ?></b></u><p/>

<p><u><b><?= Yii::t('frontend','Consolidated financial data') ?></b></u><p/>
