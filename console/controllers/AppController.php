<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;

class AppController extends Controller
{
    public $writablePaths = [
        // backend
        '@backend/runtime',
        '@backend/web/assets',

        // console
        '@console/runtime',

        // frontend
        '@frontend/runtime',
        '@frontend/web/assets',
    ];

    public $executablePaths = [
        '@root/yii',
    ];

    public $generateKeysPaths = [
        '@root/.env',
    ];

    /**
     * @throws \yii\base\InvalidRouteException
     * @throws \yii\console\Exception
     */
    public function actionSetup()
    {
        $this->runAction('set-writable', ['interactive' => $this->interactive]);
        $this->runAction('set-executable', ['interactive' => $this->interactive]);
        $this->runAction('set-keys', ['interactive' => $this->interactive]);
        Yii::$app->runAction('migrate/up', ['interactive' => $this->interactive]);
        Yii::$app->runAction('migrate/up', ['migrationPath' => '@yii/rbac/migrations/', 'interactive' => false]);
        shell_exec("touch PHP_errors.log");
    }

    public function actionSetWritable()
    {
        $this->setWritable($this->writablePaths);
    }

    public function actionSetExecutable()
    {
        $this->setExecutable($this->executablePaths);
    }

    public function actionSetKeys()
    {
        $this->setKeys($this->generateKeysPaths);
    }

    public function setWritable($paths)
    {
        foreach ($paths as $writable) {
            $writable = Yii::getAlias($writable);
            Console::output("Setting writable: {$writable}");
            @chmod($writable, 0777);
        }
    }

    public function setExecutable($paths)
    {
        foreach ($paths as $executable) {
            $executable = Yii::getAlias($executable);
            Console::output("Setting executable: {$executable}");
            @chmod($executable, 0755);
        }
    }

    public function setKeys($paths)
    {
        foreach ($paths as $file) {
            $file = Yii::getAlias($file);
            Console::output("Generating keys in {$file}");
            $content = file_get_contents($file);
            $content = preg_replace_callback('/<generated_key>/', function () {
                $length = 32;
                $bytes = openssl_random_pseudo_bytes(32, $cryptoStrong);

                return strtr(substr(base64_encode($bytes), 0, $length), '+/', '_-');
            }, $content);
            file_put_contents($file, $content);
        }
    }

    public function actionReset()
    {
        Yii::$app->runAction('migrate/up', ['interactive' => $this->interactive]);
        Yii::$app->runAction('rbac/init', ['interactive' => $this->interactive]);

    }
}
