<?php

$config = [
    'name'=> 'Yii2',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'extensions' => require __DIR__ . '/../../vendor/yiisoft/extensions.php',
    'timeZone' => env('TIMEZONE'),
    'language' => env('LANGUAGE'),
    'bootstrap' => ['frontend\config\LoggerBootstrap',],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'modules' => [
        'rbac' => [
            'class' => 'developeruz\db_rbac\Yii2DbRbac',
            'params' => [
                'userClass' => 'common\models\User'
            ]
        ],
        'noty' => [
            'class' => 'lo\modules\noty\Module',
        ],
    ],
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => env('DB_DSN'),
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'tablePrefix' => env('DB_TABLE_PREFIX'),
            'charset' => 'utf8',
            'enableSchemaCache' => YII_ENV_PROD,
        ],
        'formatter' => [
            'decimalSeparator' => '.',
            'thousandSeparator' => '',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'as AccessBehavior' => [
            'class' => \developeruz\db_rbac\behaviors\AccessBehavior::className(),
        ],
        'assetManager' => [
            'class' => 'yii\web\AssetManager',
            'linkAssets' => env('LINK_ASSETS'),
            'appendTimestamp' => YII_ENV_DEV,
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                ],
            ],
        ],
        'urlManager' => [
            'class' => 'codemix\localeurls\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'languages' => ['en', 'uk'],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'encryption' => env('MAILER_ENCRYPTION'),
                'host' => env('MAILER_HOST'),
                'port' => env('MAILER_PORT'),
                'username' => env('MAILER_USERNAME'),
                'password' => env('MAILER_PASSWORD'),
            ],             
            'useFileTransport' => env('MAILER_USE_FILE_TRANSPORT'),
        ],
        'cache' => [
            'class' => YII_ENV_DEV ? 'yii\caching\DummyCache' : 'yii\caching\FileCache',
        ],
    ],
];

return $config;
