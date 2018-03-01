<?php
use yii\helpers\Url;
?>
<div class="panel panel-body">
    <a href="<?= Url::toRoute('/site/device_add')?>">
        <div class="btn btn-success"> ДОБАВИТЬ АВТОМАТ </div>
    </a>
    <hr>
<div class="table-responsive"> 
    <table class="table"> 
        <thead> <tr>  <th>IMEI</th> <th>Data</th> <th>Events</th>  </tr> </thead> 
        <tbody> 
    <?php 
        if(isset($devices) AND is_array($devices)){
            foreach ($devices as $device){
				
    ?>

            <tr> 
				<th scope="row">
					<?=$device->id_dev?>
				</th> 
				<td>
					<b><?=stripslashes($device->name)?></b> 
					<?=stripslashes($device->organization)?> <?=stripslashes($device->city)?> <?=stripslashes($device->adress)?>
				</td> 
				<td>
                <?php if(Yii::$app->user->can('edit_dev')){ ?>
                    <a href="/site/dev?id=<?=$device->id?>" ><i class="fa fa-cogs" aria-hidden="true"></i> </a>
                ....
				<?php } ?>
                    <a href="/frontend/site/price&imei=<?=$device->id_dev?>" > <i class="fa fa-usd" aria-hidden="true"> </i> </a>
					<?php if(Yii::$app->user->can('del_dev')){ ?>
                ....
                    <a href="#" data-id="<?=$device->id?>" class="dell-dev"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                <?php } ?>
                </td>  
            </tr>     
    <?php
            }
        }
    
    ?>

           
        </tbody> 
    </table>

</div>    
    
</div>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
<script>
$('.dell-dev').on('click', function(e){
    var result = confirm('Вы точно хотите удалить автомат?');
    e.stopPropagation();
    e.preventDefault();

    if (result) {
        var id = $(this).attr('data-id');
        location.href = '/frontend/site/deldev?id=' + id;
    }

});
</script>
