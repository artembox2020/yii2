<?php

use yii\helpers\Html;
use frontend\models\UserBlacklist;

/* @var $userId int */

?>

<div class="net-manager-new">
    <div class="block-user-dv hidden" id="card-confirm">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <button type="button" class="close text-right pr-4 pt-4">
                    <svg height="32" class="octicon octicon-x" viewBox="0 0 12 16" version="1.1" width="24" aria-hidden="true">
                        <path fill-rule="evenodd" d="M7.48 8l3.75 3.75-1.48 1.48L6 9.48l-3.75 3.75-1.48-1.48L4.52 8 .77 4.25l1.48-1.48L6 6.52l3.75-3.75 1.48 1.48L7.48 8z"></path>
                    </svg>
                </button>
                <h4 class="modal-title p-2 text-center fw600">
                    <?= Yii::t('map', 'Card Confirmation') ?> 
                </h4>
                <div class="block"></div>
                <div class="form-group">
                    <label for="card_no">
                        <?= Yii::t('map', 'Card number') ?>
                    </label>
                    <input 
                        class="form-control"
                        size="6"
                        name="card_no"
                        placeholder="<?= Yii::t('map', 'Card number') ?>"
                    />
                </div>
                <br>
                <div class="btn-wrap m-3">
                    <button type="button" class="btn btn-success btn-block confirm-btn">
                        <?= Yii::t('map', 'Confirm') ?>
                    </button>
                    <button type="button" class="btn btn-outline-info btn-block confirm-cancel-btn">
                        <?= Yii::t('map','No Confirm') ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- card confirm js -->
<?= Yii::$app->view->render('@frontend/views/map/js/card-confirm', ['userId' => $userId]) ?>
