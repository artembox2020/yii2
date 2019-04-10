<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
?>
<?php $menu = []; ?>
    <b>
        <?= $this->render('_sub_menu', [
            'menu' => $menu,
        ]) ?>
    </b>
    <br/>
    <h4><?= Yii::t('common','Worker Card') ?></h4>

    <p>
        <?= Html::a('['.Yii::t('frontend','Update').']', ['edit-employee', 'id' => $model->id], ['class' => 'btn btn-primary', 'style' => 'color: #fff;']) ?>
        <?= Html::a('['.Yii::t('frontend','Delete').']', ['delete-employee', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'style' => 'color: #fff;',
            'data' => [
                'confirm' => Yii::t('common','Delete Confirmation'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        [
            'label' => Yii::t('common','Number'),
            'value' => $model->id
        ],
        [
            'label' => Yii::t('common','Fistname Lastname Patronymic'),
            'value' => $model->userProfile->firstname. " ".$model->userProfile->lastname
        ],
        [
            'label' => Yii::t('common','Position'),
            'value' => $model->userProfile->position
        ],
        [
            'label' => Yii::t('common','Server Rights'),
            'value' => $model->getUserRoleName($model->id)
        ],
        [
            'label' => Yii::t('common','Birthday'),
            'value' => date("d.m.Y",$model->userProfile->birthday)
        ]

    ],
])
?>
