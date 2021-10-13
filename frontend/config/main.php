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
    'layout' => env('LAYOUT'),
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
        'cookieLanguageSelector' => [
            'class' => 'gugglegum\Yii2\Extension\CookieLanguageSelector\Component',
            'defaultLanguage' => 'uk-UA',
            'validLanguages' => ['uk-UA', 'ru-RU', 'en-US'],
        ],
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
        'allowedIPs' => explode(",", env('DEBUG_ALLOWED_IPS')),
    ];
    $config['bootstrap'][] = 'gii';
    $config['bootstrap'][] = 'cookieLanguageSelector';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => explode(",", env('DEBUG_ALLOWED_IPS')),
    ];
}

return $config;
