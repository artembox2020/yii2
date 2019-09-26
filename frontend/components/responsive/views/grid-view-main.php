<?php

use yii\grid\GridView;

/* @var $dataProvider DataProvider */
/* @var $filterModel Model */
/* @var $options array */
/* @var $columns array */
/* @var $summary bool|string */
/* @var $rowOptions array */
/* @var $tableOptions array */

?>

<?=
    yii\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $filterModel,
        'columns' => $columns,
        'options' => $options,
        'summary' => $summary,
        'rowOptions' => $rowOptions,
        'tableOptions' => empty($tableOptions) ? [ 'class' => $gridClass] : $tableOptions,
    ]);
?>