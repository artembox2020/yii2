<?php

/* @var $menuItems array */
/* @var $userMenuItem array */

?>
<header class="top-menu container-fluid">
    <div class="logo ml-1 ml-md-5">
        <a href="<?= Yii::$app->homeUrl ?>">
            <img src="<?= Yii::getAlias('@storageUrl/main-new') ?>/img/logo.png" alt="Постирайка">
        </a>
    </div>
    <div class="menu-items mr-5">
        <?php foreach ($menuItems as $item): ?>
            <a href ="<?= $item['url'][0] ?>" >
                <?= $item['label'] ?>
            </a>
        <?php endforeach; ?>

        <a href="<?= $userMenuItem['url'] ?>" data-method="post" tabindex="-1" style="  white-space:nowrap;">
            <div class="img-wrapper">
                <img src="<?= Yii::getAlias('@storageUrl/main-new') ?>/img/user.svg" alt="user">
            </div>
            <div class="label-wrapper">
                <?= $userMenuItem['label'] ?? null ?>
            </div>
        </a>
    </div>
    <div class="burger-menu">
        <svg 
            class="svg-inline--fa fa-bars fa-w-14 fa-2x white"
            aria-hidden="true"
            focusable="false"
            data-prefix="fas"
            data-icon="bars"
            role="img"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 448 512"
            data-fa-i2svg=""
        >
            <path 
                fill="currentColor"
                d="M16 132h416c8.837 0 16-7.163 16-16V76c0-8.837-7.163-16-16-16H16C7.163 60 0 67.163 0 76v40c0 8.837 7.163 16 16 16zm0 160h416c8.837 0 16-7.163 16-16v-40c0-8.837-7.163-16-16-16H16c-8.837 0-16 7.163-16 16v40c0 8.837 7.163 16 16 16zm0 160h416c8.837 0 16-7.163 16-16v-40c0-8.837-7.163-16-16-16H16c-8.837 0-16 7.163-16 16v40c0 8.837 7.163 16 16 16z"
            >
            </path>
        </svg>
        <i class="fas fa-bars fa-2x white"></i>
    </div>
    <div class="mobile-menu">
        <?php foreach ($menuItems as $item): ?>
            <a href ="<?= $item['url'][0] ?>" ><?= $item['label'] ?></a>
        <?php endforeach; ?>
        <a href="<?= $userMenuItem['url'] ?>" data-method="post">
            <img src="<?= Yii::getAlias('@storageUrl/main-new') ?>/img/user.svg" alt="user">
            <div style="margin:-4px 0 0 24px;">
                <?= $userMenuItem['label'] ?? null ?>
            </div>
        </a>
    </div>
</header>