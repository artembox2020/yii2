<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\GdMashine */

$this->title = Yii::t('frontend', 'Create Gd Mashine');
?>
<?php $menu = []; ?>
<b>
    <?= $this->render('/net-manager/_button_back') ?>
</b><br>
<b>
    <?= $this->render('/net-manager/_sub_menu', [
        'menu' => $menu,
    ]) ?>
</b><br>
<div class="gd-mashine-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
