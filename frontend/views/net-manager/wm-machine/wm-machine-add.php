<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model frontend\models\WmMashine */
/* @var $imes frontend\models\Imei */


?>
<?php $menu = []; ?>
<b>
    <?= $this->render('/net-manager/_sub_menu', [
        'menu' => $menu,
    ]) ?>
    <div class="imei-update">

        <h1><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
                'model' => $model,
            'imeis' => $imeis
        ]) ?>

    </div>
