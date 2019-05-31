<?php
use yii\helpers\Url;
use frontend\models\Org;

?>
<div class="panel panel-body">
<?php if(Yii::$app->user->can('editCompanyData')){?>
    <a href="<?= Url::toRoute('/site/orgadd')?>">
        <div class="btn btn-success"> ДОБАВИТЬ ОРГАНИЗАЦИЮ </div>
    </a>
    <hr>
<?php }?>
    <div class="table-responsive"> 
    <table class="table"> 
        <thead> <tr>  <th>ID</th> <th>NAME</th>  </tr> </thead> 
        <tbody> 
    <?php 
        if(isset($org) AND is_array($org)){
            foreach ($org as $org_all){
				
    ?>

            <tr> 
				<th scope="row">
					<?=$org_all->id?>
				</th> 
				<td>
					<b><?=stripslashes($org_all->name_org)?></b> 
					<b><?php ///var_dump( Org::get_org_user());?></b> 
				</td> 
				
				<td>

                    <a href="/frontend/site/vieworg?id=<?=$org_all->id?>" ><i class="fa fa-eye" aria-hidden="true"></i> </a>
					<?php if(Yii::$app->user->can('administrator') or Yii::$app->user->can('del_org') or Yii::$app->user->can('edit_org')){?>
                ....
					<a href="/frontend/site/editorg?id=<?=$org_all->id?>" ><i class="fa fa-cogs" aria-hidden="true"></i> </a>
                ....
                    <a href="#" data-id="<?=$org_all->id?>" class="dell-dev"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
					<?php }?>
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
    var result = confirm('Вы точно хотите удалить организацию?');
    e.stopPropagation();
    e.preventDefault();

    if (result) {
        var id = $(this).attr('data-id');
        location.href = '/frontend/site/delorg?id=' + id;
    }

});
</script>