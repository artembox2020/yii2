
<div class="panel">
    <div class="panel-heading">
        <?=Yii::t('frontend', 'Add Device')?>
        <hr>
    </div>
    <div class="panel-body">
        <div id="nw-ress">
      
        </div>
        <div id="nw-add-f">
            <form role="form" id="nw-add-dev-form">

                <div class="form-group">
                    <label for="name"><?=Yii::t('frontend', 'Name of device')?></label>
                    <input type="text" class="form-control" id="name" name="name" value="CB" readonly>
                </div>
                
                <div class="form-group">
                    <label for="id_dev"><?=Yii::t('frontend', 'IMEI')?></label>
                    <input type="text" class="form-control" id="id_dev" name="id_dev" placeholder="<?=Yii::t('frontend', 'Enter IMEI')?>">
                 </div>
                

                <div class="form-group">
                    <label for="organization"><?=Yii::t('frontend', 'Organization')?></label>
                    <input type="text" class="form-control" id="organization" name="organization" placeholder="<?=Yii::t('frontend', 'Organization')?>">
                </div>           

                <div class="form-group">
                    <label for="city"><?=Yii::t('frontend', 'City')?></label>
                    <input type="text" class="form-control" id="city" name="city" placeholder="<?=Yii::t('frontend', 'City')?>">
                </div>                

                <div class="form-group">
                    <label for="adress"><?=Yii::t('frontend', 'Adress')?></label>
                    <input type="text" class="form-control" id="adress" name="adress" placeholder="<?=Yii::t('frontend', 'Adress')?>">
                </div>        

                <div class="form-group">
                    <label for="name_cont"><?=Yii::t('frontend', 'Name of contact person')?></label>
                    <input type="text" class="form-control" id="name_cont" name="name_cont" placeholder="<?=Yii::t('frontend', 'Name of contact person')?>">
                </div>               

                <div class="form-group">
                    <label for="tel_cont"><?=Yii::t('frontend', 'Phone of contact person')?></label>
                    <input type="text" class="form-control" id="tel_cont" name="tel_cont" placeholder="<?=Yii::t('frontend', 'Phone of contact person')?>">
                </div>            

                <div class="form-group">
                    <label for="operator"><?=Yii::t('frontend', 'Telecommunications operator')?></label>
                    <select class="form-control" id="operator" name="operator">
                        <option value="Life">Life</option>
                        <option value="MTS">MTS</option>
                        <option value="Kievstar">Kievstar</option>
                        <option value="Beeline">Beeline</option>
                    </select>
                </div>            

                <div class="form-group">
                    <label for="n_operator"><?=Yii::t('frontend', 'Phone number')?></label>
                    <input type="text" class="form-control" id="n_operator" name="n_operator" placeholder="<?=Yii::t('frontend', 'Phone number')?>">
                </div>  
                <div class="form-group">
                    <label for="kp"><?=Yii::t('frontend', 'Тип купюроприемника')?></label>
                    <input type="text" class="form-control" id="kp" name="kp" placeholder="<?=Yii::t('frontend', 'Тип купюроприемника')?>">
                </div>  
                <div class="form-group">
                    <label for="kps"><?=Yii::t('frontend', 'Вместимость купюроприемника, шт')?></label>
                    <input type="text" class="form-control" id="kps" name="kps" placeholder="<?=Yii::t('frontend', 'Вместимость купюроприемника, шт')?>">
                </div>     
                <div class="form-group">
                    <label for="balans"><?=Yii::t('frontend', 'Предельный баланс')?></label>
                    <input type="text" class="form-control" id="balans" name="balans" placeholder="<?=Yii::t('frontend', 'Предельный баланс')?>">
                </div>                  
                <hr>  
                <button type="submit" class="btn btn-group-justified btn-success"><?=Yii::t('frontend', 'SAVE')?></button>
            </form>
        </div>    
    </div>
</div>

<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
<script>
    
    $('#id_dev').keyup(function(){
        var mnn = $(this).val();
        $('#name').val(mnn+'CB');
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
        if(im.length > 2){
            $.ajax({
                type: 'GET',
                url: '/frontend/site/savedevice',
                cache: false,
                data: data,
                success: function (datar) {
                    if(datar != 'no'){
                        $('#nw-ress').hide(90000);  
                        $('#nw-add-f').show(90000);
                        $('#nw-add-dev-form').trigger('reset');
                        $('#nw-ress').prepend('<div class="alert alert-success">'+datar+'</div>');
						location.href = '/frontend/site/devices';
                    }
                }
            });            
            
        } else{
            $('#nw-ress').prepend('<div class="alert alert-danger"><?=Yii::t('frontend', 'Fill all necessary fields')?></div>');
            setTimeout(function(){
                $('#nw-ress').hide(100);  
                $('#nw-add-f').show(200);
            }, 3000);
        }
        $('#nw-ress').append('<div class="loader"><div class="sk-spinner sk-spinner-three-bounce"><div class="sk-bounce1"></div><div class="sk-bounce2"></div><div class="sk-bounce3"></div></div></div>');
        
        
    });
</script>