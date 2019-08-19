<?php

/* @var $cards backend\models\search\CardSearch */
/* @var $user common\models\User */

?>
<section class="card-of-card_info pl-3 pl-md-4 pb-4">
    <span class="title">
        <h3><?= Yii::t('map', 'User Card') ?></h3>
    </span>
    <table class="table-sm table-bordered card-tab">
        <tbody>
            <tr>
                <th><?= Yii::t('map', 'LFP') ?></th>
                <td>
                    <?= $user->userProfile->firstname.' '.$user->userProfile->lastname ?>
                </td>                    
            </tr>
            <tr>
                <th><?= Yii::t('map', 'Login') ?></th>
                <td>
                    <?= $user->username ?>
                </td>                    
            </tr>
            <tr>
                <th><?= Yii::t('map', 'Birthday') ?></th>
                <td>
                    <?= date('d-m-Y', $user->userProfile->birthday) ?>
                </td>                    
            </tr>
            <tr>
                <th><?= Yii::t('map', 'University') ?></th>
                <td></td>                    
            </tr>
            <tr>
                <th><?= Yii::t('map', 'Faculty') ?></th>
                <td></td>                    
            </tr>
            <tr>
                <th><?= Yii::t('map', 'Group') ?></th>
                <td></td>                    
            </tr>
            <tr>
                <th><?= Yii::t('map', 'Hostel') ?></th>
                <td></td>                    
            </tr>
            <tr>
                <th><?= Yii::t('map', 'About myself') ?></th>
                <td>
                    <?= $user->userProfile->other ?>
                </td>                    
            </tr>
        </tbody>
    </table>
    <div class="avatar">
        <img 
            src="/storage/avatars/<?= $user->userProfile->getAvatarPath() ?>"
            alt="<?= Yii::t('map', 'Avatar') ?>"
        />
    </div>
    <ul class="cards-list d-flex flex-column">
        <li class="fw600"><?= Yii::t('map', 'Cards') ?></li>
        <?php foreach ($cards->findCardsByUserId($user->id) as $cardNo): ?>
            <li><?= $cardNo ?></li>
        <?php endforeach; ?>
    </ul>
</section>