<?php

use common\models\User;
use frontend\services\custom\Debugger;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use frontend\models\NavItem;
use frontend\services\globals\Entity;
use lo\modules\noty\Wrapper;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    $brand = Yii::$app->name;
    $brand_url = Yii::$app->homeUrl;
    $entity = new Entity();

    NavBar::begin([
        'brandLabel' => $brand,
        'brandUrl' => $brand_url,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);

    $role = ArrayHelper::map(Yii::$app->authManager->getRolesByUser(Yii::$app->user->id), 'description', 'description');
    foreach ($role as $key => $val) {
        $role_description = $key;
    }
    $role_name = Yii::$app->user->identity->username;
    $userRole = \Yii::$app->authManager->getRolesByUser(Yii::$app->user->id);

    if (empty($role_description)) {
        $userRole = '';
    } else {
        $userRole = $role_description;
    }

    $menuItems = [];

    if (empty($role_name)) {
        $role_name = Yii::t('frontend', 'role not defined');
    }

    $menuItems[] = [
        'label' => $role_name . ' (' . $userRole .')',
        'url' => '#',
        'items' => [
            [
                'label' => Yii::t('frontend', 'Logout'),
                'url' => ['/account/sign-in/logout'],
                'linkOptions' => ['data-method' => 'post'],
            ],
        ],
    ];

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end() ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?php Wrapper::widget(); ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-right">Sense Server</p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
