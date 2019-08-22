<?php

use frontend\models\CustomerCards;
use frontend\models\Transactions;
use yii\helpers\Html;

/* @var $card \frontend\models\CustomerCards */

?>
<div class="grid-view-filter grid-view-filter-setting actions-block">
    <div class="filter-prompt">
        <label class="log-setting">
            <?= Yii::t('frontend', 'Actions') ?>
        </label> 
        <span class="glyphicon glyphicon-plus"></span>
    </div>
    <div class="filter-menu hidden">
    <?php echo Html::beginForm('', 'post', ['class' => 'journal-filter-form form-inline']); ?>
        <div class="filter-container">
            <div class="form-group">
                <label>
                    <input type="checkbox" name="to_block" value="1">
                    <?= $card->status == CustomerCards::STATUS_INACTIVE ? Yii::t('map', 'Unblock') : Yii::t('map', 'Block') ?>
                </label>
                <label>
                    <input type="checkbox" name="to_refill" value="1">
                    <?= Yii::t('map', 'Refill') ?>
                </label>
                <br>
                <input
                    type="number"
                    placeholder="<?= Yii::t('map', 'Amount') ?>"
                    readonly="readonly" name="refill_amount"
                    min="<?= Transactions::MIN_REFILL_AMOUNT ?>"
                    max="<?= Transactions::MAX_REFILL_AMOUNT ?>"
                />
                <input type="hidden" name="card_no" value="<?= $card->card_no ?>" />
            </div>
        </div>
        <div class="form-group form-button-group align-center">
            <?php echo Html::submitButton(Yii::t('frontend', 'Submit'), ['class' => 'btn btn-primary']); ?>
            <?php echo Html::resetButton(Yii::t('frontend', 'Cancel'), ['class' => 'btn btn-primary btn-cancel']); ?>
        </div>
    <?php  echo Html::endForm(); ?>
    </div>
</div> 