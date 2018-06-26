<?php
/* @var $this yii\web\View */
/* @var $model frontend\models\Company */
/* @var $users common\models\User */
/* @var $balanceHolders */
/* @var $addressBalanceHolders */
/* @var $users */
/* @var $profile common\models\UserProfile */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<?php $menu = []; ?>
<b>
<?= $this->render('_sub_menu', [
        'menu' => $menu,
    ]) ?>
</b>
<div class="account-default-users">
    <h1><?= Html::encode($this->title) ?></h1>
    <table>
        <tbody>
            <tr>
                <th><?= Yii ::t('common','Number') ?></th>
                <th><?= Yii ::t('common','Name') ?></th> 
                <th><?= Yii ::t('common','Position') ?></th>
                <th><?= Yii ::t('common','Access') ?></th>
                <th><?= Yii ::t('common','Administration') ?></th>
            </tr>
        <?php foreach($users as $user): ?>    
            <tr>
                <td><b><?= $user->id ?></b></td>
                <td><?= $user->userProfile->firstname. " ".$user->userProfile->lastname ?></td> 
                <td><?= $user->userProfile->position ?></td>
                <td><?= $user->getUserRoleName($user->id) ?></td>
                <td><?= Html::a(Yii::t('frontend', 'update'), ['edit-employee', 'id' =>$user->id]) ?>|<?= Html::a(Yii::t('frontend', 'view'), ['view-employee', 'id' =>$user->id]) ?>|Delete</td>
            </tr>
        <?php endforeach; ?>    
        </tbody>
    </table>
    <br/>
    <?= Html::a("[".Yii::t('frontend', 'Create user')."]", ['account/default/create'], ['style' => 'background-color: #5c87b2; color: aliceblue']) ?>
</div>
