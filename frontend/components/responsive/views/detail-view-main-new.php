<?php

use yii\widgets\DetailView;

/* @var $model Model */
/* @var $attributes array */
/* @var $options array */

?>

<div class="net-manager-new detail-view-main-new">
    <section class="knu-shevchenko container-fluid mt-4 pl-0 pl-md-5">
        <div class="row address-tab pl-4 pl-md-1">
            <?=
                DetailView::widget([
                    'model' => $model,
                    'attributes' => $attributes,
                    'options' => $options
                ])
            ?>
        </div>
    </section>
</div>