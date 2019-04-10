<?php
ini_set("display_errors", -1);

use yii\helpers\Html; 
?>
<style>
    #nw-ress{
        display: none;
    }
</style>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
<div class="panel">
    <div class="panel-heading">
        <?=Yii::t('DashboardModule.base', 'Свойства автомата')?>
        <hr>
    </div>
    <div class="panel-body">
        <div id="nw-ress">
      
        </div>
        <div id="nw-add-f">
            <form role="form" id="nw-add-dev-form">
                <input type="hidden"  value="<?=$device[0]['id']?>" id="id" name="id" >
        
                <div class="form-group">
                    <label for="name"><?=Yii::t('DashboardModule.base', 'Name of device')?></label>
                    <input type="text" class="form-control" value="<?=Html::encode(stripcslashes($device[0]['name']))?>"  id="name" name="name" readonly>
                </div>

                <div class="form-group">
                    <label for="id_dev"><?=Yii::t('DashboardModule.base', 'IMEI')?></label>
                    <input type="text" class="form-control" value="<?=$device[0]['id_dev']?>" id="id_dev" name="id_dev" placeholder="<?=Yii::t('DashboardModule.base', 'Enter IMEI')?>">
                 </div>


                <div class="form-group">
                    <label for="organization"><?=Yii::t('DashboardModule.base', 'Organization')?></label>
                    <input type="text" class="form-control" value="<?=Html::encode(stripslashes($device[0]['organization']))?>"  id="organization" name="organization" placeholder="<?=Yii::t('DashboardModule.base', 'Organization')?>">
                </div>           

                <div class="form-group">
                    <label for="city"><?=Yii::t('DashboardModule.base', 'City')?></label>
                    <input type="text" class="form-control" value="<?=Html::encode(stripslashes($device[0]['city']))?>"  id="city" name="city" placeholder="<?=Yii::t('DashboardModule.base', 'City')?>">
                </div>                

                <div class="form-group">
                    <label for="adress"><?=Yii::t('DashboardModule.base', 'Adress')?></label>
                    <input type="text" class="form-control" value="<?=Html::encode(stripslashes($device[0]['adress']))?>"  id="adress" name="adress" placeholder="<?=Yii::t('DashboardModule.base', 'Adress')?>">
                </div>        

                <div class="form-group">
                    <label for="name_cont"><?=Yii::t('DashboardModule.base', 'Name of contact person')?></label>
                    <input type="text" class="form-control" value="<?=Html::encode(stripslashes($device[0]['name_cont']))?>"  id="name_cont" name="name_cont" placeholder="<?=Yii::t('DashboardModule.base', 'Name of contact person')?>">
                </div>               

                <div class="form-group">
                    <label for="tel_cont"><?=Yii::t('DashboardModule.base', 'Phone of contact person')?></label>
                    <input type="text" class="form-control" value="<?=Html::encode(stripslashes($device[0]['tel_cont']))?>"  id="tel_cont" name="tel_cont" placeholder="<?=Yii::t('DashboardModule.base', 'Phone of contact person')?>">
                </div>            

                <div class="form-group">
                    <label for="operator"><?=Yii::t('DashboardModule.base', 'Telecommunications operator')?></label>
                    <select class="form-control" id="operator" name="operator">
                        <option value="<?=$device[0]['operator']?>"><?=$device[0]['operator']?></option>
                        <option value="Life">Life</option>
                        <option value="MTS">MTS</option>
                        <option value="Kievstar">Kievstar</option>
                        <option value="Beeline">Beeline</option>
                    </select>
                </div>            

                <div class="form-group">
                    <label for="n_operator"><?=Yii::t('DashboardModule.base', 'Phone number')?></label>
                    <input type="text" class="form-control" value="<?=Html::encode(stripslashes($device[0]['n_operator']))?>"  id="n_operator" name="n_operator" placeholder="<?=Yii::t('DashboardModule.base', 'Phone number')?>">
                </div>  
                <div class="form-group">
                    <label for="kp"><?=Yii::t('DashboardModule.base', 'Тип купюроприемника')?></label>
                    <input type="text" class="form-control" value="<?=Html::encode(stripslashes($device[0]['kp']))?>" id="kp" name="kp" placeholder="<?=Yii::t('DashboardModule.base', 'Тип купюроприемника')?>">
                </div>  
                <div class="form-group">
                    <label for="kps"><?=Yii::t('DashboardModule.base', 'Вместимость купюроприемника, шт')?></label>
                    <input type="text" class="form-control" value="<?=Html::encode(stripslashes($device[0]['kps']))?>" id="kps" name="kps" placeholder="<?=Yii::t('DashboardModule.base', 'Вместимость купюроприемника, шт')?>">
                </div>                
                <div class="form-group">
                    <label for="balans"><?=Yii::t('DashboardModule.base', 'Предельный баланс')?></label>
                    <input type="text" class="form-control" value="<?=Html::encode(stripslashes($device[0]['balans']))?>" id="balans" name="balans" placeholder="<?=Yii::t('DashboardModule.base', 'Предельный баланс')?>">
                </div>                   
                <hr>  
                <button type="submit" class="btn btn-group-justified btn-success"><?=Yii::t('DashboardModule.base', 'SAVE')?></button>
            </form>
        </div>    
    </div>
</div>


<script>
    
    $('#id_dev').keyup(function(){
        var mnn = $(this).val();
        $('#name').val(mnn+'CP');
    });
    
    
    $('#nw-add-dev-form').off().on('submit', function(e){
        e.preventDefault();
        e.stopPropagation();
        $('#nw-ress').html('');  
        
        var data = $(this).serialize();
        $('#nw-add-f').hide(200);
        $('#nw-ress').show(100);
        var im = $('#id_dev').val();
        var nm = $('#name').val();

        if(im.length > 2 && nm.length > 2){
    
            $.ajax({
                type: 'GET',
                url: '/site/add_dev',
                cache: false,
                data: data,
                success: function (datar) {
                  
                    $('#nw-ress').prepend('<div class="alert alert-success">'+datar+'</div>');
                    setTimeout(function(){

                        $('#nw-ress').hide(100);  
                        $('#nw-add-f').show(200);
                        $('#nw-add-dev-form').trigger('reset');  
                        location.href = '/site/devices';
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