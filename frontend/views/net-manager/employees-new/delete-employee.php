<div
    class="modal fade"
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
            <h5 class="modal-title p-2 text-center fw600">
                <?= Yii::t('common', 'Delete Employee Confirmation') ?> 
                <span class="username">PRO</span>?
            </h5>
            <div class="btn-wrap m-3">
                <button type="button" class="btn btn-success btn-block erase-btn">
                    <?= Yii::t('frontend','Delete') ?>
                </button>
                <button type="button" class="btn btn-outline-info btn-block erase-cancel-btn">
                    <?= Yii::t('frontend','No Delete') ?>
                </button>
            </div>
        </div>
    </div>
</div>