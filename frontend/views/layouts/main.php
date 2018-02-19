<?php

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use frontend\models\NavItem;
use lo\modules\noty\Wrapper;
use frontend\models\Org;

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
    if (Yii::$app->user->can('administrator')) {
        $brand = Yii::$app->name;
        $brand_url = Yii::$app->homeUrl;
    } else {
        $org = Org::get_org_name(Yii::$app->user->id);
        $brand = $org['name_org'];
        $brand_url = Yii::$app->homeUrl . '/site/vieworg?id=' . $org['id'];
    }
    NavBar::begin([
        'brandLabel' => $brand,
        'brandUrl' => $brand_url,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [
        [
            'label' => Yii::t('frontend', 'Users'),
            'url' => ['/account/default/users'],
            'visible' => Yii::$app->user->can('administrator'),
        ],
        [
            'label' => Yii::t('frontend', 'Monitoring'),
            'url' => ['/site/mntr'],
            'visible' => Yii::$app->user->can('mntr'),
        ],
        [
            'label' => Yii::t('frontend', 'DevManager'),
            'url' => ['/site/devices'],
            'visible' => Yii::$app->user->can('devices'),
        ],
        [
            'label' => Yii::t('frontend', 'Zurnal'),
            'url' => ['/site/zurnal'],
            'visible' => Yii::$app->user->can('zurnal'),
        ],
        [
            'label' => Yii::t('frontend', 'Dlogs'),
            'url' => ['/site/dlogs'],
            'visible' => Yii::$app->user->can('dlogs'),
        ],
        [
            'label' => 'Ещё',
            'url' => '#',
            'visible' => Yii::$app->user->can('administrator'),
            'items' => [
                [
                    'label' => 'Менеджер организаций',
                    'url' => ['/site/org'],
                    'visible' => Yii::$app->user->can('administrator'),
                ],
            ],
        ],

    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => Yii::t('frontend', 'Login'), 'url' => ['/account/sign-in/login']];
    } else {
        $role = ArrayHelper::map(Yii::$app->authManager->getRolesByUser(Yii::$app->user->id), 'description', 'description');
        foreach ($role as $key => $val) {
            $role_name = $key;
        }
        $menuItems[] = [
            'label' => $role_name,
            'url' => '#',
            'items' => [
                ['label' => Yii::t('frontend', 'Settings'), 'url' => ['/account/default/settings']],
                [
                    'label' => Yii::t('frontend', 'Backend'),
                    'url' => env('BACKEND_URL'),
                    'linkOptions' => ['target' => '_blank'],
                    'visible' => Yii::$app->user->can('administrator'),
                ],
                [
                    'label' => Yii::t('frontend', 'Logout'),
                    'url' => ['/account/sign-in/logout'],
                    'linkOptions' => ['data-method' => 'post'],
                ],
            ],
        ];
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => ArrayHelper::merge(NavItem::getMenuItems(), $menuItems),
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
        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
