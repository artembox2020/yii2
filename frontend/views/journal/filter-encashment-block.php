<?php

use frontend\models\Imei;
use yii\helpers\Html;
use yii\widgets\Pjax;
use \yii\jui\AutoComplete;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $params array */
/* @var $submitFormOnInputEvents string */
/* @var $imeis array */
/* @var $addresses array */
?>

<h1 class="encashment-title"><?= Html::encode($this->title) ?></h1>

<?php
    Pjax::begin(['id' => 'journal-pjax-container']);
    echo Html::beginForm('', 'get', ['class' => 'journal-filter-form form-inline', 'data-pjax' => 1]);
?>

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

<div class="form-group enhanced-z-index">
        <label for="type_packet"><?= Yii::t('frontend', 'Date From') ?></label>
        <?php
            echo DatePicker::widget([
                'model' => $searchModel,
                'attribute' => 'from_date',
                'dateFormat' => Imei::DATE_PICKER_FORMAT,
                'options' => [
                    'placeholder' => Yii::t('frontend', 'Enter Date From'),
                    'id' => 'mashine-from-date',
                    'autocomplete' => 'off'
                ]
            ]);
        ?>
</div>

<div class="form-group enhanced-z-index">
        <label for="type_packet"><?= Yii::t('frontend', 'Date To') ?></label>
        <?php
            echo DatePicker::widget([
                'model' => $searchModel,
                'attribute' => 'to_date',
                'dateFormat' => Imei::DATE_PICKER_FORMAT,
                'options' => [
                    'placeholder' => Yii::t('frontend', 'Enter Date To'),
                    'id' => 'mashine-to-date',
                    'autocomplete' => 'off'
                ]
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

<!--  Hidden Print Form -->
<?php
    echo Html::beginForm('/encashment-journal/print', 'post', ['class' => 'encashment-print-form form-inline', 'data-pjax' => '1']);
?>

<div class="form-group">
    <?= Html::hiddenInput('html', ''); ?>
</div>

<div class="form-group">
    <?= Html::hiddenInput('filename', ''); ?>
</div>

<div class="form-group">
    <?= Html::hiddenInput('caption', ''); ?>
</div>

<div class="form-group">
    <?= Html::hiddenInput('title', ''); ?>
</div>

<?php
    echo Html::endForm();
?>
<br>