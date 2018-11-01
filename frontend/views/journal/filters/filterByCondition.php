<?php

use frontend\models\JlogSearch;
use yii\helpers\Html;
use yii\jui\DatePicker;
use frontend\models\Imei;

$filterConditions = JlogSearch::getAccessibleFiltersByColumnName($columnName);
?>
<div class="filter-container">
    <div class="filter-type">
        <span class ="glyphicon glyphicon-play"></span>
        <?= $name ?>
    </div>
    <div class="filter-group">
        <div class="form-group">
            <?= Html::dropDownList("filterCondition[{$columnName}]", 
                    $params['filterCondition'][$columnName], $filterConditions
                );
            ?>
        </div>
        <div class="form-inputs-container">
            <div class="form-group">
                <?= Html::input("text",  "val1[{$columnName}]", 
                        $params['val1'][$columnName],
                        [
                            'class' => 'form-control input-val1',
                            'placeholder' => Yii::t('frontend', 'Argument 1')
                        ]
                    )
                ?>
            </div>
            <div class="form-group">
                <?php if ($columnName != 'date'): ?>
                <?= 
                    Html::input("text",  "val2[{$columnName}]", 
                        $params['inputValue'][$columnName],
                        [
                            'class' => 'form-control input-val2',
                            'placeholder' => Yii::t('frontend', 'Argument 2'),
                        ]
                    )
                ?>
                <?php else: ?>
                <?=
                    DatePicker::widget([
                        'model' => $searchModel,
                        'attribute' => "val2[{$columnName}]",
                        'dateFormat' => Imei::DATE_PICKER_FORMAT,
                        'options' => [
                            'placeholder' => Yii::t('frontend', 'Argument 2'),
                            'class' => 'form-control input-val2',
                            'autocomplete' => 'off',
                            'name' => "val2[{$columnName}]",
                        ],
                    ]);
                ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>