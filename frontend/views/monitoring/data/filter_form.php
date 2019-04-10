<?php

    use yii\helpers\Html;
    use \yii\jui\AutoComplete;

    echo Html::beginForm('', 'get', ['class' => 'monitoring-filter-form form-inline', 'data-pjax' => 0]);
?>

    <div class="form-group monitoring-shapter">
        <label for="type_packet"><?= Yii::t('frontend', 'Monitoring Shapter') ?></label>
        <?= Html::dropDownList(
                'monitoring_shapter', 
                'all',
                $monitoringShapters,
                [
                    'class' => 'form-control'
                ]
            );
        ?>
    </div>

    <div class="form-group">

        <?= AutoComplete::widget([
                'name' => 'address',
                'options' => [
                    'placeholder' => Yii::t('frontend', 'Begin to type address'),
                    'class' => 'form-control',
                    'size' => 30
                ],
                'value' => $params['address'],
                'clientOptions' => [
                    'source' => $addresses,
                    'autoFill' => false,
                ],
            ]);
        ?>

    </div>

    <div class="form-group">

        <?= AutoComplete::widget([
                'name' => 'imei',
                'options' => [
                    'placeholder' => Yii::t('frontend', 'Begin to type imei'),
                    'class' => 'form-control',
                    'size' => 30
                ],
                'value' => $params['imei'],
                'clientOptions' => [
                    'source' => $imeis,
                    'autoFill' => false,
                ],
            ]);
        ?>

    </div>

    <div class="form-group form-inline">
        <?= Html::dropDownList(
                'sortOrder', 
                $params['sortOrder'],
                $sortOrders,
                [
                    'class' => 'form-control'
                ]
            );
        ?>
    </div>

    <?php
        echo Html::endForm();
    ?>