<?php

/* @var $action string */
/* @var $imgUrl string */
/* @var $userId int */

?>

<button
    class="btn-transparent actions-btn"
    data-toggle = "modal"
    data-target = "#del-coworker"
    data-delete-id = "<?= $userId ?>"
>
    <img src="<?= $imgUrl ?>" alt="<?= Yii::t('map', $action) ?>">
    <span class="color-edit fz12 pl-2"><?= Yii::t('map', $action) ?></span>
</button>