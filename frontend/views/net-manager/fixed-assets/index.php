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

    <!--<a href="/net-manager/osnovnizasoby">[--><?//= Yii::t('frontend', 'washing machines') ?><!--]</a>-->
    <!--<a href="/net-manager/osnovnizasoby">[--><?//= Yii::t('frontend', 'Gel dispensers') ?><!--]</a>-->
    <br><br>
    <a href="/net-manager/fixed-assets">[<?= Yii::t('frontend', 'Storage') ?>]</a>
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
            ['attribute' => 'number_device',
                'label' => Yii::t('frontend', 'Number Device'),
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
                    return Html::a(Html::encode($dataProvider->getCurrentStatus($dataProvider->status)), Url::to(['/net-manager/wm-machine-update', 'id' => $dataProvider->number_device]));
                },
                'format' => 'raw',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t('common', 'Actions'),
                'template' => '{update} {delete}',
                'buttons' => [
                    'update' => function ($url,$model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['#', 'id' =>$model->id]);
                    },

                    'delete' => function($url, $model) {
                        if($model->is_deleted) return '';
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['#', 'id' => $model->id],
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
