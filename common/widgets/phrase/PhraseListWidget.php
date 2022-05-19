<?php

namespace common\widgets\phrase;

use common\models\Phrase;
use Yii;
use yii\base\Widget;
use common\models\PhraseSearch;

class PhraseListWidget extends Widget
{
    private $params = [];

    public function init()
    {

    }

    public function run()
    {
        $searchModel = new PhraseSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('phrase_list', [
            'model' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public static function createPostBind()
    {
        return (new PhraseListWidget())->render('post_phrase_bind');
    }
}

?>