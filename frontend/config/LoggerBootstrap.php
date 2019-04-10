<?php

namespace frontend\config;

use frontend\services\logger\src\LoggerDto;
use frontend\services\logger\src\storage\LoggerDaoStorage;
use Yii;
use yii\base\BootstrapInterface;

class LoggerBootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $container = Yii::$container;
        $container->setSingleton('LoggerService');
        $container->set('frontend\services\logger\src\storage\StorageInterface', function() {
            return new LoggerDaoStorage(Yii::$app->db);
        });
        $container->set('frontend\services\logger\src\LoggerDtoInterface', function() {
            return new LoggerDto;
        });
    }
}
