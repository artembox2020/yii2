<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use frontend\models\Imei;
use frontend\models\Jlog;
use frontend\services\custom\Debugger;
use frontend\services\globals\EntityHelper;
use yii\widgets\Pjax;
use \yii\jui\AutoComplete;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\JlogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="jlog-index">
    <h3 align="center"><?= Yii::t('frontend', 'Address Logs') ?></h3>
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
    
    <div class="form-group hidden">
        <?= Html::hiddenInput('selectionName', $params['selectionName']); ?>
    </div>

    <div class="form-group hidden">
        <?= Html::hiddenInput('selectionCaretPos', $params['selectionCaretPos']); ?>
    </div>

    <div class="form-group hidden">
        <?= Html::submitButton(Yii::t('frontend', 'Submit'), ['class' => 'btn btn-primary', 'id' => 'filter-submit-btn']); ?>
    </div>

    <?php
        echo Html::endForm();
        echo $submitFormOnInputEvents;
    ?>

    <?php
        // renders appropriate packet 
        echo $journalController->renderAppropriatePacket($params, $dataProvider);
    ?>

    <?php
        echo $removeRedundantGrids;
        echo $columnFilterScript;
        Pjax::end();
    ?>
</div>
