<?php

use yii\helpers\Html;

echo Html::beginForm('', 'get', ['class' => 'grid-view-filter-form', 'data-pjax' => '']);

$filterTypeCondition = Yii::t('frontend', 'Filter By Condition');
$filterTypeValue = Yii::t('frontend', 'Filter By Value');
?>
<div class="grid-view-filter">
    <div class="filter-prompt">
        <?= Yii::t('frontend', 'Filter') ?>
        <span class="glyphicon glyphicon-plus"></span>
    </div>
    <div class="filter-menu">
        <?= $this->render('/journal/filters/filterByCondition',
                ['name'=> $filterTypeCondition, 'columnName' => $name, 'params' => $params]
            );
        ?>
        <br/>
        <?= $this->render('/journal/filters/filterByValue',
                ['name'=> $filterTypeValue, 'columnName' => $name, 'params' => $params]
            );
        ?>
        
        <div class = "form-group form-button-group">
            <?php echo Html::submitButton(Yii::t('frontend', 'Submit'), ['class' => 'btn btn-primary']); ?>
            <?php echo Html::resetButton(Yii::t('frontend', 'Cancel'), ['class' => 'btn btn-primary btn-cancel']); ?>
        </div>
    </div>
</div>

<?php echo Html::endForm(); ?>
