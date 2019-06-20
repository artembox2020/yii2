<?php

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/params.php'
);

$config = [
    'id' => 'app-frontend',
    'homeUrl' => Yii::getAlias('@frontendUrl'),
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'frontend\controllers',
    'modules' => [
        'account' => [
            'class' => 'frontend\modules\account\Module',
        ],
        'forward' => [
            'class' => 'frontend\modules\forward\Module',
        ],
        'noty' => [
            'class' => 'lo\modules\noty\Module',
        ],
        'api' => [
            'class' => 'frontend\modules\api\v1\Module',
        ],
    ],
    'components' => [
        'formatter' => [
            'decimalSeparator' => '.',
        ],
        'request' => [
            'cookieValidationKey' => env('BACKEND_COOKIE_VALIDATION_KEY'),
            'csrfParam' => '_csrf-frontend',
			'baseUrl' => '',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'loginUrl'=>['/account/sign-in/login'],
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'app-frontend',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'response' => [
            'formatters' => [
                \yii\web\Response::FORMAT_JSON => [
                    'class' => 'yii\web\JsonResponseFormatter',
                ],
            ],
        ],
        'urlManager' => require __DIR__ . '/_urlManager.php',
        'cache' => require __DIR__ . '/_cache.php',
        'dbCommandHelper' => [
            'class' => 'frontend\components\db\DbCommandHelper'
        ],
        'dbCommandHelperOptimizer' => [
            'class' => 'frontend\components\db\DbCommandHelperOptimizer'
        ],
        'cookieLanguageSelector' => [
            'class' => 'gugglegum\Yii2\Extension\CookieLanguageSelector\Component',
            'defaultLanguage' => 'uk-UA',
            'validLanguages' => ['uk-UA', 'ru-RU', 'en-US'],
        ],
//        'mailer' => [
//            'class' => 'yii\swiftmailer\Mailer',
////            One more suggestion is to use port "465" and encryption as "ssl" instead of port "587", encryption "tls".
//            'transport' => [
//                'class' => 'Swift_SmtpTransport',
//                'host' => 'smtp.gmail.com',
//                'username' => 'sense.servers@gmail.com',
//                'password' => 'senseserver010203',
//                'port' => '587',
//                'encryption' => 'tls',
//            ],
//            'useFileTransport' => false,
//        ],
    ],
    'as beforeAction' => [
        'class' => 'common\behaviors\LastActionBehavior',
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['127.0.0.1', '::1', '95.47.114.243', '134.249.146.11'],
    ];
    $config['bootstrap'][] = 'gii';
    $config['bootstrap'][] = 'cookieLanguageSelector';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '::1', '95.47.114.243', '134.249.146.11'],
    ];
}

if (YII_ENV_PROD) {
    // maintenance mode
    $config['bootstrap'] = ['maintenance'];
    $config['components']['maintenance'] = [
        'class' => 'common\components\maintenance\Maintenance',
        'enabled' => env('MAINTENANCE_MODE'),
        'route' => 'maintenance/index',
        'message' => env('MAINTENANCE_MODE_MESSAGE'),
        // year-month-day hour:minute:second
        'time' => env('MAINTENANCE_MODE_TIME'), // время окончания работ
    ];
}

return $config;
