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
                'label' => Yii::t('frontend', 'Address')
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
                'value' => function($model)
                {

                    return !empty($model['imei']) ? $model['imei'] : '---';
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
