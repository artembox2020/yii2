<?php

use yii\helpers\Html;
use frontend\models\UserBlacklist;

/* @var $action string */
/* @var $companyId int */
/* @var $authorId int */
/* @var $user \common\models\User */

?>

<div
    class="modal fade block-user-dv"
    id="del-coworker"
    tabindex="-1"
    role="dialog"
    aria-labelledby="delete-coworker"
    aria-hidden="true"
>
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <button
                type="button"
                class="close text-right pr-4 pt-4"
                data-dismiss="modal"
                aria-label="<?= Yii::t('common', 'Close') ?>"
            >
                <svg height="32" class="octicon octicon-x" viewBox="0 0 12 16" version="1.1" width="24" aria-hidden="true">
                    <path fill-rule="evenodd" d="M7.48 8l3.75 3.75-1.48 1.48L6 9.48l-3.75 3.75-1.48-1.48L4.52 8 .77 4.25l1.48-1.48L6 6.52l3.75-3.75 1.48 1.48L7.48 8z"></path>
                </svg>
            </button>
            <h4 class="modal-title p-2 text-center fw600">
                <?= Yii::t('map', $action.' User Confirmation') ?> 
                <span class="username">"<?= $user->username ?>"</span>
            </h4>
            <br>
            <?php if ($action == 'Block' && ($blockReason = Yii::t('map', 'Choose block reason'))): ?>
                <div class="form-group">
                    <label><?= $blockReason ?></label>
                    <?= Html::dropDownList(
                        'block-reason',
                        [
                            'prompt' => $blockReason,
                        ],
                        UserBlacklist::getBlockReasons(),
                        ['class' => 'form-control']
                    )
                    ?>
                </div>
            <?php endif; ?>
            <div class="btn-wrap m-3">
                <button type="button" class="btn btn-success btn-block block-btn">
                    <?= Yii::t('map', $action) ?>
                </button>
                <button type="button" class="btn btn-outline-info btn-block erase-cancel-btn">
                    <?= Yii::t('map','No '.$action) ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- block user script -->
<?= Yii::$app->view->render(
    '/map/js/block-user',
    [
        'userId' => $user->id
    ]
)
?>