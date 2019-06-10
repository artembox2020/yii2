<?php

use yii\helpers\Html;

/* @var $date_setting int */

?>
<div class="grid-view-filter grid-view-filter-setting">
    <div class="filter-prompt">
        <?= Yii::t('logs', 'Log settings') ?>  <span class="glyphicon glyphicon-plus"></span>
    </div>
    <div class="filter-menu hidden">
        <span style="white-space: nowrap;"><?= Yii::t('logs', 'Date and time setting') ?></span>
        <div class="filter-container">

            <?= \yii\helpers\Html::radioList(
                'date_setting',
                empty($date_setting) ? '0' : $date_setting,
                [
                    1 => Yii::t('logs', 'Display by arrival time'),
                    0 => Yii::t('logs', 'Display by event time')
                ]
            )
            ?>
        </div>
        <div class="form-group form-button-group">
            <?php echo Html::submitButton(Yii::t('frontend', 'Submit'), ['class' => 'btn btn-primary']); ?>
            <?php echo Html::resetButton(Yii::t('frontend', 'Cancel'), ['class' => 'btn btn-primary btn-cancel']); ?>
        </div>
    </div>
</div>
<script>
    var gridViewFilterSetting = document.querySelector('.grid-view-filter-setting');
    var filterPrompt = gridViewFilterSetting.querySelector('.filter-prompt');
    var filterMenu = gridViewFilterSetting.querySelector('.filter-menu');
    var resetButton = gridViewFilterSetting.querySelector('button[type=reset]');

    filterPrompt.querySelector('.glyphicon').onclick = function() {
        if (!filterMenu.classList.contains('hidden')) {
            filterMenu.classList.add('hidden');
            this.classList.remove('glyphicon-minus');
            this.classList.add('glyphicon-plus');
        } else {
            filterMenu.classList.remove('hidden');
            this.classList.remove('glyphicon-plus');
            this.classList.add('glyphicon-minus');
        }
    }

    resetButton.onclick = function() {
        filterMenu.classList.add('hidden');
        filterPrompt.querySelector('.glyphicon').classList.remove('glyphicon-minus');
        filterPrompt.querySelector('.glyphicon').classList.add('glyphicon-plus');
    }

</script>