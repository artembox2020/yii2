<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use frontend\models\ImeiDataSearch;

/* @var $this yii\web\View */
/* @var $imeis array */
/* @var $addresses array */
/* @var $params array */
/* @var $searchModel ImeiDataSearch */

?>
<b>
<?= $this->render('_sub_menu', [
        'menu' => [],
    ]) ?>
</b>
<br/><br/>

<?php
    Pjax::begin(['id' => 'modem-pjax-grid-container']);
?>

<div class="modem-history">

    <div class="modem-history-form-container">
        <?= Yii::$app->view->render('modem_form', [
            'params' => [],
            'imeis' => $imeis,
            'addresses' => $addresses,
            'params' => $params,
            'dateFormat' => $dateFormat,
            'searchModel' => $searchModel
        ])
        ?>
    </div>

    <br>

    <?= yii\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'address_name',
                'label' => Yii::t('frontend', 'Address'),
                'format' => 'raw',
                'value' => function($model) {

                    return Html::a(
                        $model['address_name'],
                        ['/address-balance-holder/view', 'id' => $model['address_id']],
                        ['target' => '_blank']
                    );
                }
            ],
            [
                'attribute' => 'created_at',
                'label' => Yii::t('frontend', 'Created At'),
                'value' => function($model) use($dateFormat)
                {

                    return date($dateFormat, $model['created_at']);
                }
            ],
            [
                'attribute' => 'imei',
                'label' => Yii::t('frontend', 'Imei'),
                'format' => 'raw',
                'value' => function($model)
                {
                    if (empty($model['imei'])) {

                        return '---';
                    }

                    return Yii::$app->commonHelper->linkByType(
                        Yii::$app->commonHelper::OBJECT_TYPE_IMEI, $model['imei']
                    );
                }
            ]
        ]
    ])
    ?>
</div>

<?= Yii::$app->view->render('js/modem-history', []) ?>

<?php
    Pjax::end();
?>
