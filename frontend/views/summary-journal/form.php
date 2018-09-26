<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use \yii\jui\AutoComplete;
use yii\jui\DatePicker;

/* @var $params array */
/* @var $months array */
/* @var $years array */
?>
<?php 
    echo Html::beginForm('', 'get', ['class' => 'summary-journal-form']);
?>
<div class="col-md-4">
    <div class="form-group">
        <label for="month"><?= Yii::t('frontend', 'Select month') ?></label>
        <?= Html::dropDownList(
            'month', 
            $params['month'] ? $params['month'] : date('m'),
            $months,
            [
                'class' => 'form-control'
            ]
        );
        ?>
    </div>
</div>

<div class="col-md-4">
    <div class="form-group">
        <label for="year"><?= Yii::t('frontend', 'Select year') ?></label>
        <?= Html::dropDownList(
            'year', 
            $params['year'] ? $params['year'] : date('Y'),
            $years,
            [
                'class' => 'form-control'
            ]
        );
        ?>
    </div>
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