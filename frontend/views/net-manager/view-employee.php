<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<h4><?= Yii::t('common','WorkerCard') ?></h4>
<table>
    <tbody>
        <tr>
            <th><?= Yii::t('common','Number') ?></th>
            <th><?= Yii::t('common','Flp') ?></th> 
            <th><?= Yii::t('common','Position') ?></th>
            <th><?= Yii::t('common','ServRight') ?></th>
            <th><?= Yii::t('common','Birthday') ?></th>
            <th><?= Yii::t('common','Administration') ?></th>
        </tr>
        <tr>
            <td><b><?= $model->id ?></b></td>
            <td><?= $model->userProfile->firstname. " ".$model->userProfile->lastname ?></td> 
            <td><?= $model->userProfile->position ?></td>
            <td><?= $model->getUserRoleName($model->id) ?></td>
            <td><?= date("d.m.Y",$model->userProfile->birthday) ?></td>
            <td><?= Html::a(Yii::t('frontend', 'update'), ['edit-employee', 'id' =>$model->id]) ?> | Delete</td>
        </tr>
    </tbody>
</table>