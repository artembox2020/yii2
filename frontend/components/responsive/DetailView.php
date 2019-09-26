<?php

namespace frontend\components\responsive;

use yii\base\Widget;
use yii\helpers\Html;

/**
 * Extends yii\widgets\DetailView widget to be responsive widget
 * Class DetailView
 * @package frontend\components\responsive
 */
class DetailView extends Widget {

    public $model;
    public $attributes;
    public $options;
    public $optionsType;

    const OPTIONS_TYPE_RESPONSIVE = 1;
    const OPTIONS_TYPE_NONE = 0;

    /**
     * Init method
     */
    public function init() {
        parent::init();

        if ($this->optionsType === null) {
            $this->optionsType = self::OPTIONS_TYPE_RESPONSIVE;
        }

        if ($this->options === null) {

            switch ($this->optionsType) {
                case self::OPTIONS_TYPE_RESPONSIVE:
                    $this->options = [
                        'class' => 'table table-responsive table-striped table-bordered table-sm col-12 col-md-6 fz16'
                    ];
                    break;
                default:
                    $this->options = [];
            }
        }
    }

    /**
     * Main widget method
     * 
     * @return string 
     */
    public function run() {

        return $this->render(
            'detail-view-'.\Yii::$app->layout,
            [
                'model' => $this->model,
                'attributes' => $this->attributes,
                'options' => $this->options
            ]
        );
    }
}
?>