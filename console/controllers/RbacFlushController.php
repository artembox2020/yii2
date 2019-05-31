<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;
use common\models\User;

class RbacFlushController extends Controller
{
    /**
     * Сброс всех привелегий и создание привелегий и ролей по умолчанию
     * Присвоение роли Super Administrator пользователю с id = 1
     * Run command: yii rbac-flush/init
     * @throws \yii\base\Exception
     */
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        //Create permissions
        $loginToBackend = $auth->createPermission('loginToBackend');
        $loginToBackend->description = 'Can login to backend';
        $auth->add($loginToBackend);

        $changeCompany = $auth->createPermission('changeCompany');
        $changeCompany->description = 'Can change company';
        $auth->add($changeCompany);

        $viewCompanyData = $auth->createPermission('viewCompanyData');
        $viewCompanyData->description = 'Can view company data';
        $auth->add($viewCompanyData);

        $editCompanyData = $auth->createPermission('editCompanyData');
        $editCompanyData->description = 'Can edit company data';
        $auth->add($editCompanyData);

        $viewTechData = $auth->createPermission('viewTechData');
        $viewTechData->description = 'Can view tech data';
        $auth->add($viewTechData);

        $editTechData = $auth->createPermission('editTechData');
        $editTechData->description = 'Can edit tech data';
        $auth->add($editTechData);

        $viewFinData = $auth->createPermission('viewFinData');
        $viewFinData->description = 'Can view fin data';
        $auth->add($viewFinData);

        $editFinData = $auth->createPermission('editFinData');
        $editFinData->description = 'Can edit fin data';
        $auth->add($editFinData);


        //Create roles
        $user = $auth->createRole(USER::ROLE_USER);
        $user->description = 'User';
        $auth->add($user);

        $manager = $auth->createRole(User::ROLE_FINANCIER);
        $manager->description = 'Financier';
        $auth->add($manager);
        $auth->addChild($manager, $viewFinData);
        $auth->addChild($manager, $editFinData);

        $tech = $auth->createRole(User::ROLE_TECHNICIAN);
        $tech->description = 'Technician';
        $auth->add($tech);
        $auth->addChild($tech, $viewTechData);
        $auth->addChild($tech, $editTechData);

        $admin = $auth->createRole(User::ROLE_ADMINISTRATOR);
        $admin->description = 'Administrator';
        $auth->add($admin);
        $auth->addChild($admin, $viewCompanyData);
        $auth->addChild($admin, $editCompanyData);
        $auth->addChild($admin, $manager);
        $auth->addChild($admin, $tech);

        $super_admin = $auth->createRole(User::ROLE_SUPER_ADMINISTRATOR);
        $super_admin->description = 'Super Administrator';
        $auth->add($super_admin);
        $auth->addChild($super_admin, $loginToBackend);
        $auth->addChild($super_admin, $changeCompany);
        $auth->addChild($super_admin, $admin);

        $auth->assign($super_admin, 1);

        Console::output('Success! RBAC roles has been added.');
    }
}
