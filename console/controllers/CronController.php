<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;
use console\models\CronTask;

class CronController extends Controller
{
    public function actionRun()
    {
        $tasks = CronTask::find()->all();
        foreach ($tasks as $task) {
            echo strtotime($task->updated_at) . ' ' . $task->timeout . ' ' . time();
            if (strtotime($task->updated_at) + $task->timeout > time()) {
                continue;
            }

            $controllerName = "\\console\controllers\\" . ucfirst($task->controller) . 'Controller';
            $actionName = 'action' . $task->action;
            $controller = new $controllerName($task->controller, 'console');
            $controller->$actionName($task->params);

            $task->updated_at = date("Y-m-d H:i:s", time());
            $task->update();
        }
    }
}