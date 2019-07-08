<?php

use frontend\services\globals\EntityHelper;
use frontend\models\ImeiDataSearch;
use frontend\models\ImeiData;

/* @var $data array */

?>
<div class="container-fluid mt-5" id="tab-fin">
    <table class="table table-bordered table-sm table-responsive-lg margtop mx-auto">
        <?= Yii::$app->view->render("/monitoring/main-new/shapter-block") ?>
        <?= Yii::$app->view->render("/monitoring/main-new/header-block", ['searchModel' => new ImeiDataSearch()]) ?>
        <tbody>
        <?php foreach ($data as $item): ?>
            <tr class="upper-row">
                <?= Yii::$app->view->render("/monitoring/main-new/common-block", ['item' => $item, 'rowspan' => 1]) ?>
                <?= Yii::$app->view->render(
                    "/monitoring/main-new/financial-block",
                    ['item' => $item, 'rowspan' => 1, 'searchModel' => new ImeiDataSearch()]
                )
                ?>
          </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>