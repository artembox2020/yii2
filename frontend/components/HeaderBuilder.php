<?php

namespace frontend\components;

use Yii;
use yii\base\Component;
use yii\helpers\Html;
use frontend\services\globals\Entity;
use yii\helpers\ArrayHelper;

/**
 * Class HeaderBuilder
 * @package frontend\components
 */
class HeaderBuilder extends Component {

    /**
     * Builds header depending on layout
     * 
     * @param string $layout
     * 
     * @return string
     */
    public function makeHeader($layout)
    {
        $brand = Yii::$app->name;
        $brand_url = '/';
        $entity = new Entity();
        $userMenuItems = [];

        if (!empty($user = $entity->getUser())) {
            if (!empty($user->company)) {
                $company = $user->company;
                $brand = $company->name;
            }
        }

        if (Yii::$app->user->isGuest) {
            $menuItems[] = ['label' => Yii::t('frontend', 'Login'), 'url' => ['/account/sign-in/login']];
        } else {
            $role = ArrayHelper::map(
                Yii::$app->authManager->getRolesByUser(Yii::$app->user->id), 
                'description', 'description'
            );
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

            if ( yii::$app->user->can('viewTechData') ) {
                $monitoring_url = '/monitoring/technical';
            }

            if ( yii::$app->user->can('viewFinData') ) {
                $monitoring_url = '/monitoring/financial';
            }

            if ( yii::$app->user->can('viewTechData') && yii::$app->user->can('viewFinData') ) {
                $monitoring_url = '/monitoring';
            }

            if (empty($role_name)) {
                $role_name = Yii::t('frontend', 'role not defined');
            }

            $menuItems = [
                [
                    'label' => Yii::t('frontend', 'Monitoring'),
                    'url' => [$monitoring_url],
                ],
                [
                    'label' => Yii::t('nav-items', 'Net'),
                    'url' => ['/net-manager'],
                ],
                [
                    'label' => Yii::t('frontend', 'Zurnal'),
                    'url' => ['/summary-journal'],
                ],
                [
                    'label' => Yii::t('nav-items', 'Encashment'),
                    'url' => ['/encashment-journal/index'],
                    'visible' => Yii::$app->user->can('viewFinData'),
                ],
                [
                    'label' => Yii::t('frontend', 'Dlogs'),
                    'url' => ['/journal/index?sort=-date'],
                    'visible' => Yii::$app->user->can('viewTechData'),
                ],
            ];

            $userMenuItems =  [
                [
                    'label' => Yii::t('frontend', 'Users'),
                    'url' => ['/net-manager/employees'],
                    'visible' => Yii::$app->user->can('manager'),
                ],
                [
                    'label' => Yii::t('frontend', 'Company'),
                    'url' => ['/account/default/tt'],
                    'visible' => Yii::$app->user->can('administrator'),
                ],
                [
                    'label' => Yii::t('frontend', 'Logout'),
                    'url' => ['/account/sign-in/logout'],
                ]
            ];
        }

        return Yii::$app->view->render(
            "@frontend".'/views/layouts/'.$layout.'/header',
            [
                'menuItems' => $menuItems,
                'userMenuItems' => $userMenuItems
            ]
        );
    }
}