<?php

use yii\helpers\Html;
use frontend\models\JlogSearch;

if (empty($searchModel)) {
   $searchModel = new JlogSearch();
}

echo Html::beginForm('', 'get', ['class' => 'grid-view-filter-form', 'data-pjax' => '']);

$filterTypeCondition = Yii::t('frontend', 'Filter By Condition');
$filterTypeValue = Yii::t('frontend', 'Filter By Value');

$sortType = $params['sort'] == $name ? 'arrow-up' : null;
if (empty($sortType)) {
    $sortType = $params['sort'] == '-'.$name ? 'arrow-down' :  'tag';
}

?>
<div class="grid-view-filter">
    <div class="filter-prompt">
        <?= Yii::t('frontend', 'Filter') ?>
        <span class="glyphicon glyphicon-plus"></span>
    </div>
    <div class="filter-menu" data-field = "<?= $name ?>">
        <p class="text-muted order">
            <small>
                <?= Yii::t('frontend', 'Order') ?>
                <span class = "glyphicon glyphicon-<?= $sortType ?>"></span>
            </small>
        </p>
        <?= $this->render('/journal/filters/filterByCondition',
                ['name'=> $filterTypeCondition, 'columnName' => $name, 'params' => $params, 'searchModel' => $searchModel]
            );
        ?>
        <br/>
        <?= $this->render('/journal/filters/filterByValue',
                ['name'=> $filterTypeValue, 'columnName' => $name, 'params' => $params, 'searchModel' => $searchModel]
            );
        ?>

        <div class = "form-group form-button-group">
            <?php echo Html::submitButton(Yii::t('frontend', 'Submit'), ['class' => 'btn btn-primary']); ?>
            <?php echo Html::resetButton(Yii::t('frontend', 'Cancel'), ['class' => 'btn btn-primary btn-cancel']); ?>
        </div>
    </div>
</div>

<?php echo Html::endForm(); ?>
