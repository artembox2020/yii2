<?php

namespace common\widgets\phrase;

use Yii;
use yii\base\Widget;
use common\models\Phrase;

class PhraseWidget extends Widget
{
    private $params = [];

    public function init()
    {

    }

    public static function createPhrase()
    {
        if (empty(Yii::$app->request->post()['PhraseForm'])) {

            return $this->goBack();
        }

        $post = Yii::$app->request->post()['PhraseForm'];
        $phrase = new Phrase();
        $phrase->setScenario('DEFAULT');
        $phrase->setAttributes([
            'phrase' => $post['phrase'],
            'trans'  => $post['trans'],
            'lang'   => $post['lang'],
            'sid'    => Yii::$app->session->id,
        ]);
        $phrase->save();
        Yii::$app->response->statusCode = 200;

        return static::widget();
    }

    public static function updateContainerOnSubmit($context, $container)
    {
        $context->registerJS(
            <<<JS
        jQuery(function($) {
            let updatePhraseList = function()
            {
                if ($(".phrase-form").hasClass('invisible')) {
                    if ($("$container")) {
                        $.pjax.reload({container: '$container', async: true});
                    }
                } else {
                    setTimeout(updatePhraseList, 200);
                }
            };
    
            $('.phrase-form form').on('submit', function() {
                setTimeout(updatePhraseList, 200);    
            });

        });
JS
        );
    }

    public function run()
    {
        return $this->render('phrase');
    }
}

?>