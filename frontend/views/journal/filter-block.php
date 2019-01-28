<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use \yii\jui\AutoComplete;

/* @var $this yii\web\View */
/* @var $params array */
/* @var $submitFormOnInputEvents string */
/* @var $imeis array */
/* @var $addresses array */
?>

<h1><?= Html::encode($this->title) ?></h1>

<?php
    Pjax::begin(['id' => 'journal-pjax-container']);
    echo Html::beginForm('', 'get', ['class' => 'journal-filter-form form-inline', 'data-pjax' => 1]);
?>

<div class="form-group">
    <label for="type_packet"><?= Yii::t('frontend', 'Type Packet') ?></label>
    <?= Html::dropDownList(
            'type_packet', 
            $params['type_packet'] ? $params['type_packet'] : Jlog::TYPE_PACKET_DATA, $typePackets,
            [
                'class' => 'form-control'
            ]
        );
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
    <?= Html::hiddenInput('selectionName', $params['selectionName']); ?>
</div>   

<div class="form-group">
    <?= Html::hiddenInput('selectionCaretPos', $params['selectionCaretPos']); ?>
</div> 

<div class="form-group hidden">
    <?= Html::submitButton(Yii::t('frontend', 'Submit'), ['class' => 'btn btn-primary', 'id' => 'filter-submit-btn']); ?>
</div>

<?php
    echo Html::endForm();
    echo $submitFormOnInputEvents;
?>
<br>