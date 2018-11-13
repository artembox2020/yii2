<?php

use yii\helpers\Html;
use \yii\jui\AutoComplete;
use yii\jui\DatePicker;
use yii\web\JsExpression;
use frontend\models\Imei;

/* @var $imeis array */
/* @var $addresses array */
/* @var $params array */
/* @var $searchModel ImeiDataSearch */    

?>

<?php
    echo Html::beginForm('', 'get', ['class' => 'modem-history-filter-form form-inline', 'data-pjax' => 1]);
?>
    <br>
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
                    'select' => new JsExpression("function( event, ui ) {
                        setImeiInputs(ui.item);
                        clearFormButSelectorAndSubmit('input[name=imei], input[name=imeiId]');
                    }"),
                ],
            ]);
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
                    'select' => new JsExpression("function( event, ui ) {
                        setAddressInputs(ui.item);
                        clearFormButSelectorAndSubmit('input[name=address], input[name=addressId]');
                    }"),
                ],
            ]);
        ?>

    </div>
    
    <div class="form-group">
        <?=
            DatePicker::widget([
                'model' => $searchModel,
                'attribute' => 'timestamp',
                'dateFormat' => Imei::DATE_PICKER_FORMAT,
                'options' => [
                    'placeholder' => Yii::t('frontend', 'Enter Date'),
                    'class' => 'form-control',
                    'autocomplete' => 'off',
                    'name' => 'timestamp',
                ],
                'clientOptions' => [
                    'onSelect' => new JsExpression("function(event,ui) { clearFormButSelectorAndSubmit('input[name=timestamp]'); }")
                ],
            ]);
        ?>
    </div>

    <?php if (empty($params['imeiId']) && empty($params['addressId']) && empty($params['timestamp'])): ?>
        <?= Html::hiddenInput('imeiId', $params['imeiId']) ?>
        <?= Html::hiddenInput('addressId', $params['addressId']) ?>
    <?php endif; ?>

    <div class="form-group hidden">
        <?= Html::submitButton(Yii::t('frontend', 'Submit'), ['class' => 'btn btn-primary']); ?>
    </div>

    <?php
        echo Html::endForm();
    ?>