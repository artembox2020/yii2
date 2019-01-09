<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<?php $menu = []; ?>
<b>
    <?= $this->render('/net-manager/_sub_menu', [
        'menu' => $menu,
    ]) ?>
    <h1><?= Html::encode($this->title) ?></h1>
    <!--<a href="/net-manager/osnovnizasoby">[--><?//= Yii::t('frontend', 'washing machines') ?><!--]</a>-->
    <!--<a href="/net-manager/osnovnizasoby">[--><?//= Yii::t('frontend', 'Gel dispensers') ?><!--]</a>-->
    <a href="/net-manager/fixed-assets">[<?= Yii::t('frontend', 'Storage') ?>]</a>
</b>
    <?php
    $url = \yii\helpers\BaseUrl::current();
    $url = explode("?", $url)[0];
    $script = <<< JS
    var selector = document.querySelector("[href='{$url}']");
    if (typeof selector !== "undefined" && selector !== null) {
        selector.style.color = 'green';
    }
JS;
    $this->registerJs($script);
    ?>

</b><br><br>

<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            ['label' => Yii::t('frontend', 'Inventory number'),
                'value' => function ($model) {
                    return Html::a(Html::encode($model->getInventoryNumber($model->number_device)->inventory_number), Url::to(['/net-manager/wm-machine-update', 'id' => $model->number_device]));
                },
                'format' => 'raw',
            ],
            ['attribute' => 'address.name',
                'label' => Yii::t('frontend', 'Address'),
            ],
            ['attribute' => 'lastPing',
                'label' => Yii::t('frontend', 'Last ping'),
            ],
            ['attribute' => 'warehouseTransferDate',
                'label' => Yii::t('frontend', 'Warehouse transfer date'),
            ],
            ['label' => Yii::t('frontend', 'Storage transfer date'),
                'value' => function ($dataProvider) {
                            if($dataProvider->date_transfer_from_storage) {
                                return $dataProvider->getStorageTransferDate();
                            }
                        },
                'format' => 'raw',
            ],
            ['attribute' => 'status',
                'label' => Yii::t('frontend', 'Status'),
                'value' => function ($dataProvider) {
                        if ($dataProvider->status < 2) {
                            return $dataProvider->getCurrentStatus($dataProvider->status);
                        }
                    return Html::a(Html::encode($dataProvider->getCurrentStatus($dataProvider->status)), Url::to(['/net-manager/wm-machine-update', 'id' => $dataProvider->number_device]));
                },
                'format' => 'raw',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t('common', 'Actions'),
                'template' => '{delete}',
                'buttons' => [
                    'delete' => function($url, $model) {
                        if($model->is_deleted or $model->status == 2) return '';
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['wmachine-delete', 'id' => $model->id],
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
            ]
]);
?>
