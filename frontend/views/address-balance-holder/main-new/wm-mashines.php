<?php

/* @var $item array */
/* @var $model \frontend\models\ImeiDataSearch */

?>

<div class="monitoring-new wm-mashines-block">
    <div class="table-responsives monitoring-grid-view">
        <div class="container-fluid mt-5">
            <table align="center" class="table table-bordered table-sm table-responsive-lg margtop mx-auto">
                <tbody>
                    <tr class="upper-row">
                        <?= 
                            Yii::$app->view->render(
                                '@frontend/views/monitoring/main-new/wm-mashines',
                                [
                                    'item' => $item,
                                    'model' => $model,
                                    'rowspan' => 4
                                ]
                            )
                        ?>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>