<?php

use frontend\services\globals\EntityHelper;
use frontend\models\ImeiData;
use frontend\models\ImeiDataSearch;

/* @var $data array */

?>
<div class="container-fluid mt-5" id="tab-gen">
    <table class="table table-bordered table-sm table-responsive-lg margtop mx-auto">
        <?= Yii::$app->view->render("/monitoring/main-new/shapter-block") ?>
        <?= Yii::$app->view->render("/monitoring/main-new/header-block", ['searchModel' => new ImeiDataSearch()]) ?>
        <tbody>
        <?php foreach ($data as $item): ?>
            <tr class="upper-row">
                <?= Yii::$app->view->render("/monitoring/main-new/common-block", ['item' => $item, 'rowspan' => 4]) ?>
                <?= Yii::$app->view->render(
                    "/monitoring/main-new/financial-block",
                    ['item' => $item, 'rowspan' => 4, 'searchModel' => new ImeiDataSearch()]
                )
                ?>
                <?= Yii::$app->view->render(
                    "/monitoring/main-new/technical-block",
                    ['item' => $item['technical'], 'rowspan' => 4, 'model' => new ImeiData()]
                )
                ?>
        <?php endforeach; ?>
        </tbody>
      </table>
</div>