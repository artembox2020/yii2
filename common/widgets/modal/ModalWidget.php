<?php

namespace common\widgets\modal;

use Yii;
use yii\base\Widget;

class ModalWidget extends Widget
{
    public $id;
    public $title;
    public $method;
    public $model;
    public $modelColumns;
    public $formClass;
    public $isAjax;

    public function run()
    {
        return  $this->render('modal.php', [
            'id'    => $this->id,
            'isAjax' => $this->isAjax,
            'method' => $this->method,
            'model' => $this->model,
            'title'    => $this->title,
            'modelColumns' => $this->modelColumns,
            'formClass' => $this->formClass,
        ]);
    }
}

?>