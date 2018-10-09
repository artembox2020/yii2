<?php

namespace frontend\services\globals;

use vova07\fileapi\Widget;
use Yii;

class MyFileApi extends Widget
{
    /**
     * Register widget translations.
     */
    public function registerTranslations()
    {
        if (!isset(Yii::$app->i18n->translations['uk-UA']) && !isset(Yii::$app->i18n->translations['vova07/*'])) {
            Yii::$app->i18n->translations['uk-UA'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => 'common/messages',
                'fileMap' => [
                    'uk-UA' => 'fileapi.php'
                ],
                'forceTranslation' => true
            ];
        }
    }

}
