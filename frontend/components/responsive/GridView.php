<?php

namespace frontend\components\responsive;

use yii\base\Widget;
use yii\helpers\Html;

/**
 * Extends yii\grid\GridView widget to be responsive widget
 * Class GridView
 * @package frontend\components\responsive
 */
class GridView extends Widget {

    public $dataProvider;
    public $filterModel = false;
    public $summary;
    public $columns;
    public $gridClass = false;
    public $optionsType = false;
    public $options = false;
    public $tableOptions = false;
    public $rowOptions = false;

    const OPTIONS_TYPE_RESPONSIVE = 1;
    const OPTIONS_TYPE_NONE = 0;
    const OPTIONS_DEFAULT_GRID_CLASS = 'table table-responsive table-striped table-bordered table-sm col-12 col-md-6';

    /**
     * Init method
     */
    public function init() {
        parent::init();

        if ($this->optionsType === false) {
            $this->optionsType = self::OPTIONS_TYPE_RESPONSIVE;
        }

        if ($this->gridClass === false && $this->tableOptions === false) {

            switch ($this->optionsType) {
                case self::OPTIONS_TYPE_RESPONSIVE:
                    $this->gridClass = self::OPTIONS_DEFAULT_GRID_CLASS;
                    break;
                default:
                    $this->gridClass = '';
            }
        }
    }

    /**
     * run() widget method
     * 
     * @return string
     */
    public function run() {

        return $this->render(
            'grid-view-'.\Yii::$app->layout,
            [
                'dataProvider' => $this->dataProvider,
                'filterModel' => $this->filterModel,
                'summary' => $this->summary,
                'options' => $this->options,
                'columns' => $this->columns,
                'rowOptions' => $this->rowOptions,
                'tableOptions'=> $this->tableOptions,
                'gridClass' => $this->gridClass
            ]
        );
    }
}
?>