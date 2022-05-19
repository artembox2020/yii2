<?php

use \yii\base\DynamicModel;
use yii\widgets\Pjax;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use common\widgets\phrase\PhraseListWidget;

?>
<div class="dropdown">
    <b data-toggle="dropdown">
        &nbsp;&nbsp;&nbsp;...&nbsp;&nbsp;&nbsp;
    </b>
    <ul class="dropdown-menu bg-none">
        <li>
            <button class="btn btn-info btn-add-post" type="button" data-phrase-id="<?= $data['phraseId'] ?>" data-toggle="modal" data-target="#postBind">Add a post</button>
        </li>
    </ul>
</div>
<div id="postBind" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><button class="close" type="button" data-dismiss="modal">×</button>
                <h4 class="modal-title">Post binding</h4>
            </div>
            <div class="modal-body">
                <?= PhraseListWidget::createPostBind() ?>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default" type="button" data-dismiss="modal">Закрити</button>
            </div>
        </div>
    </div>
</div>

<?php $this->registerCss(
    <<<CSS
    .bg-none {
        background-color: transparent;
        border: 0;
    }
    .dropdown-menu.bg-none {
        left: -145%;
    }
    *[data-toggle='dropdown'] {
        cursor: pointer;
    }
CSS
);
?>

<?php $this->registerJS(
    <<<JS
jQuery(function($) {
    $('body').on('submit', '.phrase-bind-form form', function () {
        $("#postBind").find('button.close').click();
    });
    $('body').on('click', '.btn-add-post', function() {
        let phraseId = $(this).attr('data-phrase-id');
        let phraseInput = $("#postBind input[name='DynamicModel[phrase_id]']");
        phraseInput.attr({'type': 'hidden', 'value': phraseId});
        phraseInput.closest('.form-group').addClass('hidden');
    });
});
JS
);