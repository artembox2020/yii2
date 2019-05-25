<?php
namespace console\controllers;

use common\models\User;
use common\rbac\OwnModelRule;
use Yii;
use yii\helpers\Console;

class TestController extends \yii\console\Controller
{
    public function actionIndex()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        $user = $auth->createRole(User::ROLE_USER);
        $user->description = 'User';
        $auth->add($user);

        // own model rule
        $ownModelRule = new OwnModelRule();
        $auth->add($ownModelRule);

        $manager = $auth->createRole(User::ROLE_FINANCIER);
        $manager->description = 'Financier';
        $auth->add($manager);
        $auth->addChild($manager, $user);

        $manager = $auth->createRole(User::ROLE_TECHNICIAN);
        $manager->description = 'Technician';
        $auth->add($manager);
        $auth->addChild($manager, $user);

        $manager = $auth->createRole(User::ROLE_ADMINISTRATOR);
        $manager->description = 'Administrator';
        $auth->add($manager);
        $auth->addChild($manager, $user);


        $loginToBackend = $auth->createPermission('loginToBackend');
        $loginToBackend->description = 'Login to backend';
        $auth->add($loginToBackend);
        $auth->addChild($manager, $loginToBackend);

        $admin = $auth->createRole(User::ROLE_SUPER_ADMINISTRATOR);
        $admin->description = 'Super Administrator';
        $auth->add($admin);
        $auth->addChild($admin, $manager);

        $auth->assign($admin, 1);

        Console::output('Success! RBAC roles has been added.');
    }
}
