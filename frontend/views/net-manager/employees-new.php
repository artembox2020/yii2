<?php

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $profile common\models\UserProfile */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $pageSize int */
/* @var $menu array */

?>
<?php $menu = []; ?>
<div class="net-manager-new">
    <?= $this->render('_sub_menu-new', [
            'menu' => $menu,
        ])
    ?>

    <?= Yii::$app->view->render('/net-manager/employees-new/employees-info', [
            'dataProvider' => $dataProvider,
            'model' => $model,
            'pageSize' => $pageSize
        ])
    ?>

    <?= Yii::$app->view->render('/net-manager/employees-new/script-employee', [
            'pageSize' => $pageSize
        ])
    ?>

<?= Yii::$app->view->render('/net-manager/employees-new/delete-employee') ?>

</div>
