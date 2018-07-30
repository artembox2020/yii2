<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
use frontend\services\custom\Debugger;
use frontend\services\globals\Entity;
use frontend\models\Imei;

/* @var $this yii\web\View */
/* @var $menu array */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel frontend\models\ImeiSearch */

?>
<?php $menu = []; ?>
<b>
    <?= $this->render('/net-manager/_sub_menu', [
        'menu' => $menu,
    ]) ?>
</b><br><br>
<b><?= Html::a(Yii::t('frontend', 'Add Washpay'), ['net-manager/washpay-create'], ['class' => 'btn btn-success', 'style' => 'color: #fff;']) ?></b>
<br/>
<?= yii\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
           'id',
           
            [
                'attribute' => 'imei',
                'format' => 'raw',
                'value' => function($model) {
                    
                    return Html::a(
                        $model->imei,
                        ['/imei/view', 'id' => $model->id]
                    );
                }
            ],
           
            [
               'attribute' => 'address',
               'format' => 'raw',
               'value' => function($model) use($addresses) {
                    $entity = new Entity();
                    $addAddress = $entity->AutoCompleteWidgetFilteredData([
                        'model' => $model,
                        'name' => 'address',
                        'url' => '/net-manager/washpay-bind-to-address',
                        'source' => $addresses,
                        'options' => [
                            'placeholder' => Yii::t('common', 'Type address')
                        ]
                    ]);

                    return $model->getRelationData(
                        ['address' => ['address', 'floor'], ', '],
                        $addAddress
                    );
               },
            ],
           
            [
               'attribute' => 'balanceHolder.name',
               'label' => Yii::t('frontend', 'Balance Holder'),
            ],
           
            [
                'attribute' => 'last_ping',
                'label' => Yii::t('frontend', 'Last ping'),
                'value' => function($model) {
                    

                    return $model->getLastPing();
                },
            ]
        ]
]); ?>
