<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use frontend\models\Imei;
use frontend\models\Jlog;
use frontend\services\globals\EntityHelper;
use yii\widgets\Pjax;
use \yii\jui\AutoComplete;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\JlogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<?php
$this->title = Yii::t('frontend', 'Events Journal');
?>

<div class="jlog-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php
        Pjax::begin(['id' => 'journal-pjax-container']);
        echo Html::beginForm('', 'get', ['class' => 'journal-filter-form form-inline', 'data-pjax' => 1]);
    ?>
    <div class="form-group">
        <label for="type_packet"><?= Yii::t('frontend', 'Type Packet') ?></label>
        <?= Html::dropDownList(
                'type_packet', 
                $params['type_packet'] ? $params['type_packet'] : '', $typePackets,
                [
                    'class' => 'form-control'
                ]
            );
        ?>
    </div>
    <div class="form-group">
        
        <?= AutoComplete::widget([
                'name' => 'imei',
                'options' => [
                    'placeholder' => Yii::t('frontend', 'Begin to type imei'),
                    'class' => 'form-control',
                    'size' => 30
                ],
                'value' => $params['imei'],
                'clientOptions' => [
                    'source' => $imeis,
                    'autoFill' => false,
                ],
            ]);
        ?>
        
    </div>
    
    <div class="form-group">

        <?= AutoComplete::widget([
                'name' => 'address',
                'options' => [
                    'placeholder' => Yii::t('frontend', 'Begin to type address'),
                    'class' => 'form-control',
                    'size' => 30
                ],
                'value' => $params['address'],
                'clientOptions' => [
                    'source' => $addresses,
                    'autoFill' => false,
                ],
            ]);
        ?>

    </div>
    
    <div class="form-group">
        <?= Html::hiddenInput('selectionName', $params['selectionName']); ?>
    </div>   
    
    <div class="form-group">
        <?= Html::hiddenInput('selectionCaretPos', $params['selectionCaretPos']); ?>
    </div> 
    
    <div class="form-group hidden">
        <?= Html::submitButton(Yii::t('frontend', 'Submit'), ['class' => 'btn btn-primary', 'id' => 'filter-submit-btn']); ?>
    </div>
    
    <?php
        echo Html::endForm();
        echo $submitFormOnInputEvents;
    ?>
    <br>
    <div class="table-responsives">

        <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'options' => [
                    'class' => 'journal-grid-view',
                ],
                'columns' => [
                    [
                        'attribute' => 'id',
                        'filter' =>  $this->render('/journal/filters/main', ['name'=> 'id', 'params' => $params]),
                    ],
                    [
                        'attribute' => 'type_packet',
                        'value' => function($model)
                        { 

                            return Jlog::getTypePacketName($model->type_packet);
                        },
                        'filter' => $this->render('/journal/filters/main', ['name'=> 'type_packet', 'params' => $params]),
                    ],
                    [
                        'attribute' => 'date',
                        'filter' => $this->render('/journal/filters/main', ['name'=> 'date', 'params' => $params, 'searchModel' => $searchModel]),
                    ],
                    [
                        'attribute' => 'imei',
                        'filter' => $this->render('/journal/filters/main', ['name'=> 'imei', 'params' => $params]),
                    ],
                    [
                        'attribute' => 'address',
                        'filter' => $this->render('/journal/filters/main', ['name'=> 'address', 'params' => $params]),
                    ],
                    [
                        'attribute' => 'events',
                        'filter' => false,
                        'content' => function($model)
                        {

                            return '';
                        }
                    ],
                ],
            ]);

        ?>
    </div>
    <?php
        echo $removeRedundantGrids;
        echo $columnFilterScript;
        Pjax::end();
    ?>
</div>
