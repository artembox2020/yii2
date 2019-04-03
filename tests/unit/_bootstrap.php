<?php

//define('YII_ENV', 'test');
defined('YII_DEBUG') or define('YII_DEBUG', true);
//require_once __DIR__ . '../../vendor/yiisoft/yii2/Yii.php';
//require __DIR__ .'../../vendor/autoload.php';
/**
 * Setting path aliases
 */
Yii::setAlias('root', realpath(__DIR__ . '/../../'));
Yii::setAlias('common', realpath(__DIR__ . '/../../common'));
Yii::setAlias('frontend', realpath(__DIR__ . '/../../frontend'));
Yii::setAlias('backend', realpath(__DIR__ . '/../../backend'));
Yii::setAlias('console', realpath(__DIR__ . '/../../console'));
Yii::setAlias('storage', realpath(__DIR__ . '/../../storage'));
Yii::setAlias('test', realpath(__DIR__ . '/../../test'));

/**
 * Setting url aliases
 */
//Yii::setAlias('frontendUrl', env('FRONTEND_URL'));
//Yii::setAlias('backendUrl', env('BACKEND_URL'));
//Yii::setAlias('storageUrl', env('STORAGE_URL'));
