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
    $brand_url = '/';
    $entity = new Entity();
//    if (Yii::$app->user->can('administrator')) {
//    } else {
        if (!empty($user = $entity->getUser())) {
            if (!empty($user->company)) {
                $company = $user->company;
                $brand = $company->name;
            }
//            $brand_url = Yii::$app->homeUrl . '/company/view?id=' . $company->id;
//        }
    }
    NavBar::begin([
        'brandLabel' => $brand,
        'brandUrl' => $brand_url,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
//    $menuItems = [
////        [
////            'label' => Yii::t('frontend', 'Users'),
////            'url' => ['/account/default/users'],
//////            'visible' => Yii::$app->user->can('administrator'),
////        ],
//        [
//            'label' => Yii::t('frontend', 'Monitoring'),
//            'url' => ['/site/mntr'],
////            'visible' => Yii::$app->user->can('mntr'),
//        ],
//        [
//            'label' => Yii::t('frontend', 'DevManager'),
//            'url' => ['/site/devices'],
////            'visible' => Yii::$app->user->can('devices'),
//        ],
//        [
//            'label' => Yii::t('frontend', 'Zurnal'),
//            'url' => ['/site/zurnal'],
////            'visible' => Yii::$app->user->can('zurnal'),
//        ],
//        [
//            'label' => Yii::t('frontend', 'Dlogs'),
//            'url' => ['/site/dlogs'],
////            'visible' => Yii::$app->user->can('dlogs'),
//        ],
////        [
////            'label' => 'Ещё',
////            'url' => '#',
//////            'visible' => Yii::$app->user->can('administrator'),
////            'items' => [
////                [
////                    'label' => 'Менеджер организаций',
////                    'url' => ['/company'],
//////                    'visible' => Yii::$app->user->can('administrator'),
////                ],
////            ],
////        ],
//
//    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => Yii::t('frontend', 'Login'), 'url' => ['/account/sign-in/login']];
    } else {
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

        $menuItems = [
//        [
//            'label' => Yii::t('frontend', 'Users'),
//            'url' => ['/account/default/users'],
//            'visible' => Yii::$app->user->can('administrator'),
//        ],
            [
                'label' => Yii::t('frontend', 'Monitoring'),
                'url' => ['/monitoring'],
                'items' => [
                    [
                        'label' => Yii::t('frontend', 'Тechnical Data'),
                        'url' => ['/monitoring/technical'],
                        'visible' => Yii::$app->user->can('viewTechData'),
                    ],
                    [
                        'label' => Yii::t('frontend', 'Financial Data'),
                        'url' => ['/monitoring/financial'],
                        'visible' => Yii::$app->user->can('viewFinData'),
                    ],
                    [
                        'label' => Yii::t('frontend', 'All'),
                        'url' => ['/monitoring'],
                        'visible' => Yii::$app->user->can('viewFinData'),
                    ],
                ]
//            'visible' => Yii::$app->user->can('mntr'),
            ],
//            [
//                'label' => Yii::t('frontend', 'Net Manager'),
//                'url' => ['/imei'],
////            'visible' => Yii::$app->user->can('devices'),
//            ],
            [
                'label' => Yii::t('nav-items', 'Net'),
                'url' => ['/net-manager'],
//            'visible' => Yii::$app->user->can('devices'),
            ],
            [
                'label' => Yii::t('frontend', 'Zurnal'),
                'url' => ['/summary-journal'],
//            'visible' => Yii::$app->user->can('zurnal'),
            ],
            [
                'label' => Yii::t('nav-items', 'Encashment'),
                'url' => ['/encashment-journal/index'],
                'visible' => Yii::$app->user->can('viewFinData'),
            ],
            [
                'label' => Yii::t('frontend', 'Dlogs'),
                'url' => ['/journal/index?sort=-date'],
//            'visible' => Yii::$app->user->can('dlogs'),
            ],
];

        if (empty($role_name)) {
            $role_name = Yii::t('frontend', 'role not defined');
        }

        $menuItems[] = [
            'label' => $role_name . ' (' . $userRole .')',
            'url' => '#',
            'items' => [
                [
                    'label' => Yii::t('frontend', 'Settings'),
                    'url' => ['/account/default/settings'],
                ],
                [
                    'label' => Yii::t('frontend', 'Company'),
                    'url' => ['/account/default/tt'],
                    'visible' => Yii::$app->user->can('administrator'),
                ],
                [
                    'label' => Yii::t('frontend', 'Users'),
                    'url' => ['/net-manager/employees'],
                    'visible' => Yii::$app->user->can('manager'),
                ],
//                [
//                        'label' => Yii::t('frontend', 'Backend'),
//                    'url' => env('BACKEND_URL'),
//                    'linkOptions' => ['target' => '_blank'],
////                    'visible' => Yii::$app->user->can('administrator'),
//                ],
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
        'items' => $menuItems,
//        'items' => ArrayHelper::merge(NavItem::getMenuItems(), $menuItems),
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
