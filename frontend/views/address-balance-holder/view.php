<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\AddressBalanceHolder */
/* @var $balanceHolder frontend\models\BalanceHolder */
/* @var $imeis frontend\models\Imei */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend', 'Address Balance Holders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

//$a = $model->getImeis()->count();
//$b = $model->getImeis();
//$c = $b->getWmMashine()->count();

//var_dump($c);die;

$bh = \frontend\models\BalanceHolder::findOne($model->balance_holder_id)
?>
<div class="address-balance-holder-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('frontend', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('frontend', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('frontend', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div>Имя <?= $model->name ?></div>
    <div>Адрес <?= $model->address ?></div>
    <div>Поверх <?= $model->floor ?></div>
    <div>Балансодержатель <?= $bh->name . ' ' . $bh->address ?></div>
    <div>Кількість поверхів <?= $model->number_of_floors ?></div>
    <div>Дата встановлення <?= $model->date_inserted ?></div>
    <div>Дата підключення до моніторінгу <?= $model->date_connection_monitoring ?></div>
    <div>Создано <?= Yii::$app->formatter->asDate($model->created_at, 'dd.MM.yyyy');?></div>
    <?php foreach ($model->imeis as $imei) : ?>
        <?php $countWmMashine[] = $imei->wmMashine ?>
    <?php endforeach; ?>
    <div>Кількість пральних машин <?= count($countWmMashine) ?></div>
<br>
    <p>
        <b>Зведені технічні данні</b><br>
        <b>Кількість циклів прання: 400000000000</b><br>
        <b>Середня кількість купюр на день (мес.): 350</b><br>
        <b>Кількість грошей: 8000000000</b><br>
        <b>Останні помилки: список</b><br>
        <b>Зведені фінансові дані</b><br>
    </p>
</div>
