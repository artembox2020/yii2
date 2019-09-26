<?php

/* @var $this yii\web\View */
/* @var $dataProvider ArrayDataProvider */
/* @var $pageSize int */

use frontend\components\responsive\GridView;
use yii\helpers\Html;
use yii\widgets\ListView;

$this->title = Yii::t('logger', 'Net change logs');
$dateFormat = "d.m.Y H:i";
$menu = [];

?>

<div class="net-manager-new">
    <?=
        $this->render('../_sub_menu-new')
    ?>
    <div class="tab-title d-flex justify-content-between mx-2 mx-md-5 my-5">
        <span class="main-title">
            <h1><?= Yii::t('logger', 'Net change logs') ?></h1>
        </span> 
        <span class="right-title text-right">
            <a href="#"><?= Yii::t('logger', 'Net change logs') ?></a>
        </span>
    </div>

    <section class="h-table ml-3 ml-md-5 mr-5">
        <table class="table table-bordered table-striped table-md table-responsive-md">
            <thead class="bold">
                <tr>
                    <td class="width80">
                        <div class="dropdown date">  
                            <?= Yii::t('logger', 'Date') ?>
                            <img src="<?= Yii::getAlias('@storageUrl/main-new') ?>/img/th-icon.png" class="pl-5" alt="icon">
                            <span class="dropdown-toggle"> </span>    
                        </div>  
                    </td>
                    <td><?= Yii::t('logger', 'Type object') ?></td>
                    <td><?= Yii::t('logger', 'Name object') ?></td>
                    <td class="width80">
                        <div class="dropdown number">
                            <?= Yii::t('logger', 'Serial number') ?>
                            <img src="<?= Yii::getAlias('@storageUrl/main-new') ?>/img/th-icon.png" class="pl-5" alt="icon">
                            <span class="dropdown-toggle"> </span>
                        </div>
                    </td>
                    <td><?= Yii::t('logger', 'Event') ?></td>
                    <td><?= Yii::t('logger', 'New state') ?></td>
                </tr>
            </thead>
            <tbody>
            <?php $counter = 0; foreach ($dataProvider->allModels as $model): ?>
                <tr class="<?= ++$counter > $pageSize ? 'hidden tr tr' : 'tr tr' ?><?= $model['id'] ?>">
                    <td>
                        <?= date($dateFormat, $model['created_at'])?>
                        <input type="hidden" class="hidden date" value="<?= $model['created_at'] ?>" />
                    </td>
                    <td>
                        <?= $model['type'] ?>  
                    </td>
                    <td>
                        <?= Yii::$app->commonHelper->linkByType($model['type'], $model['name']) ?>
                    </td>
                    <td>
                        <?= $model['number'] ?>
                        <input type="hidden" class="hidden number" value="<?= $model['number'] ?>" />
                    </td>
                    <td>
                        <?= $model['event'] ?>    
                    </td>
                    <td>
                        <?= $model['new_state'] ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </section>
    <div class="showmore <?= count($dataProvider->allModels) <= $pageSize ? 'hidden' : '' ?> text-center mt-4 mb-5">
        <button class="btn btn-primary" type="button"><?= Yii::t('logger', 'Show more') ?></button>
    </div>  
    <div class="showless hidden text-center mt-4 mb-5">
        <button class="btn btn-primary" type="button"><?= Yii::t('logger', 'Show less') ?></button>
    </div>
    <input type="hidden" class="page-size-initial" value="<?= $pageSize ?>" />
    <input type="hidden" class="page-size" value="<?= $pageSize ?>" />
</div>
<?=
    Yii::$app->view->render("/net-manager/logger/main-new/script")
?>