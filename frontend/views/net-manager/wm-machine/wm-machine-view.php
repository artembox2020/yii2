<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
use frontend\services\custom\Debugger;
/* @var $this yii\web\View */
/* @var $model frontend\models\WmMashine */
/* @var $users common\models\User */
/* @var $balanceHolders */
/* @var $addresses */
/* @var $wm_machine */
?>
<?php $menu = []; ?>
<b>
    <?= $this->render('/net-manager/_sub_menu', [
        'menu' => $menu,
    ]) ?>
</b><br><br>
<h3><?= Yii::t('frontend', 'Wash machine card'); ?></h3>
<?= DetailView::widget([
        'model'=> $model,
        'attributes' => [
                [
                    'label' => Yii::t('frontend', 'Serial number'),
                    'value' => $model->serial_number,
                ],
                [
                    'label' => Yii::t('frontend', 'Model'),
                    'value' => $model->model,
                ],
            'brand',
            [
                'label' => Yii::t('frontend', 'Date build'),
                'value' => $model->date_build,
                'format' => ['date', 'php:d.m.Y']
            ],
            [
                'label' => Yii::t('frontend', 'Date Purchase'),
                'value' => $model->date_purchase,
                'format' => ['date', 'php:d.m.Y']
            ],
            [
                'label' => Yii::t('frontend', 'Date connection to monitoring'),
                'value' => $model->date_connection_monitoring,
                'format' => ['date', 'php:d.m.Y']
            ],
            [
                'label' => Yii::t('frontend', 'Address Install'),
                'value' => $model->address->address,
            ],
            [
                'label' => Yii::t('frontend', 'Device number'),
                'value' => $model->number_device,
            ],
            [
                'label' => Yii::t('frontend', 'Last ping'),
                'value' => $model->updated_at,
                'format' => ['date','[H:i:s] dd.MM.yyyy']
            ],
    ],
]);?>
<?= Html::a(Yii::t('frontend', 'Update'), ['/net-manager/wm-machine-update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

<?= Html::a(Yii::t('frontend', 'Delete'), ['wm-mashine/delete', 'id' => $model->id], [
    'class' => 'btn btn-danger',
    'data' => [
        'confirm' => Yii::t('frontend', 'Are you sure you want to delete this item?'),
        'method' => 'post',
    ],
]) ?>
<br><br>
<div>
    <b><u>Технічні дані</u></b><br>
    ....
    <hr>
    <b><u>Фінансові дані</u></b><br>
    ....
</div>
