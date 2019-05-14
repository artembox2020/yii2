<?php

use frontend\models\Imei;
use yii\jui\DatePicker;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $radom int */
/* @var $active string */
/* @var $selector */
/* @var $controller frontend\controllers\DashboardController */
/* @var $model frontend\storages\ModemStatStorage */

?>

<div class="graph-container r<?= $random ?>" >
    <?php echo $controller->renderAjaxFormSubmission(
        $start, $end, $action, $selector, $active, $other, $actionBuilder
    )
    ?>
    <?php echo $controller->renderGraphBuilder(); ?>
    <div class="filter-prompt">
        <i class="glyphicon glyphicon-cog"></i>
    </div>

    <div class="container timestamp-interval-block grid-view-filter">
        <h4 align=center><?= Yii::t('graph', 'Filter By Date') ?></h4>
        <div class="row">
            <div class="col-md-6 col-xs-12 last-days-block filter-type">
                <span class="glyphicon glyphicon-play rotate90"></span>
                <?= Yii::t('graph', 'Show by the last days') ?>
                <div class="container-block">
                    <div class="form-group">
                        <label for="dateOptions<?=$random ?>"><?= Yii::t('graph', 'Criteria') ?></label>
                        <?= Html::dropDownList(
                            "dateOptions{$random}", 
                            $model->getDateOptionsByActive($active),
                            $model->getTimeIntervalsLines(),
                            ['class' => 'form-control']
                        );
                        ?>
                    </div>
                    <div class="form-group">
                        <label for="date"><?= Yii::t('graph', 'Date') ?></label>
                        <input class="form-control" name="date" type="date" value ="<?= $model->getDateByActive($active) ?>" />
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xs-12 days-between-block filter-type">
                <span class="glyphicon glyphicon-play rotate90"></span>
                <?= Yii::t('graph', 'Show by the certain period') ?>
                <div class="container-block">
                    <div class="form-group enhanced-z-index">
                        <label for="from_date"><?= Yii::t('frontend', 'Date From') ?></label>
                        <input class="form-control" name="from_date" type="date" value="<?= $model->getFromDateByActive($active) ?>" />
                    </div>
                    <div class="form-group enhanced-z-index">
                        <label for="to_date"><?= Yii::t('frontend', 'Date To') ?></label>
                        <input class="form-control" name="to_date" type="date" value="<?= $model->getToDateByActive($active) ?>" />
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <h4 align=center><?= Yii::t('graph', 'Filter By Address') ?></h4>
            <div class="col-md-12 col-xs-12 address-points">
                <?= $model->makeAddressPoints($start, $end, $other) ?>
            </div>    
            <div class="submit-container">
                <div class="from-group">
                    <button type="submit" class="form-control btn btn-success">
                        <?= Yii::t('graph', 'OK') ?>
                    </button>
                </div>
            </div>
        </div>    
    </div>
</div>
<script>
    var graphContainer = document.querySelector(".graph-container.r<?= $random ?>");
    var filterBlock = graphContainer.querySelector('.timestamp-interval-block');
    var checkBoxes = graphContainer.querySelectorAll('.address-points input[type=checkbox]');

    var playButtons = filterBlock.querySelectorAll('.glyphicon.glyphicon-play');
    graphBuilder.playButtonGroupClick(playButtons);

    var filterPrompts = graphContainer.querySelectorAll('.filter-prompt');
    graphBuilder.filterPromptGroupClick(filterPrompts, "<?= $random ?>");

    var submitBtn = graphContainer.querySelector(".timestamp-interval-block button[type=submit]");
    graphBuilder.submitBtnModemLevelProcess(submitBtn, <?= $random ?>, "<?= $selector ?>", filterPrompts[0], checkBoxes);

</script>