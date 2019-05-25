<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\UserProfile;
use bs\Flatpickr\FlatpickrWidget;
use vova07\fileapi\Widget as FileApi;
?>
<style>
    #nw-ress{
        display: none;
    }
</style>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
<div class="panel">
    <div class="panel-heading">
        <?=Yii::t('DashboardModule.base', 'Редактирование организации')?>
        <hr>
    </div>
    <div class="panel-body">
        <div id="nw-ress">
      
        </div>
        <div id="nw-add-f">
            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($org[0], 'name_org')->textInput(['maxlength' => true]) ?>

            <?= $form->field($org[0], 'desc')->textInput(['maxlength' => true]) ?>

                <?= $form->field($org[0], 'logo_path')->widget(FileApi::className(), [
                    'settings' => [
                        'url' => ['/site/fileapi-upload'],
                    ],
                    'crop' => true,
                    'cropResizeWidth' => 100,
                    'cropResizeHeight' => 100,
                ]) ?>
             <div class="form-group">
				<?= Html::submitButton(Yii::t('backend', 'Update'), ['class' => 'btn btn-primary']) ?>
			</div>
                <?php ActiveForm::end() ?>
                <hr>


        </div>    
    </div>
</div>


<script>


    $('#nw-add-dev-form').off().on('submit', function(e){
        e.preventDefault();
        e.stopPropagation();
        $('#nw-ress').html('');

        var data = $(this).serialize();
        $('#nw-add-f').hide(200);
        $('#nw-ress').show(100);
        var nm = $('#organization').val();

        if(nm.length > 2){

            $.ajax({
                type: 'GET',
                url: '/frontend/site/add_org',
                cache: false,
                data: data,
                success: function (datar) {
					console.log(datar);
                    $('#nw-ress').prepend('<div class="alert alert-success">'+datar+'</div>');
                    setTimeout(function(){

                        $('#nw-ress').hide(100);
                        $('#nw-add-f').show(200);
                        $('#nw-add-dev-form').trigger('reset');
                        //location.href = '/frontend/site/org';
                    }, 3000);

                }
            });

        } else{
            $('#nw-ress').prepend('<div class="alert alert-danger"><?=Yii::t('DashboardModule.base', 'Fill all necessary fields')?></div>');
            setTimeout(function(){

                $('#nw-ress').hide(100);
                $('#nw-add-f').show(200);
            }, 3000);

        }
        $('#nw-ress').append('<div class="loader"><div class="sk-spinner sk-spinner-three-bounce"><div class="sk-bounce1"></div><div class="sk-bounce2"></div><div class="sk-bounce3"></div></div></div>');


    });
</script>