<?php

use yii\widgets\DetailView;

/* @var $model Model */
/* @var $attributes array */
/* @var $options array */

?>

<?=
    DetailView::widget([
        'model' => $model,
        'attributes' => $attributes,
        'options' => $options
    ])
?>