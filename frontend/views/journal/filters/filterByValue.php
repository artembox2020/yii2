<?php

use yii\helpers\Html;
use yii\jui\DatePicker;
use frontend\models\Imei;

?>
<div class="filter-value-container">
    <div class="filter-type">
        <span class ="glyphicon glyphicon-play rotate90"></span>
        <?= $name ?>
    </div>
    <div class="filter-group">
        <div class="form-inputs-container">
            <div class="form-group">
                <div class="left-hyperlink">
                    <?php echo Html::a(Yii::t('frontend', 'Clear'), null, ['class' => 'd-inline']); ?>
                </div>    
                <br/>
                <?php if (!in_array($columnName, ['date', 'created_at', 'unix_time_offset'])): ?>
                <?= 
                    Html::input("text",  "inputValue[{$columnName}]", 
                        $params['inputValue'][$columnName],
                        [
                            'class' => 'form-control inputValue',
                            'placeholder' => Yii::t('frontend', 'Value'),
                        ]
                    )
                ?>
                <?php else: ?>
                <?=
                    DatePicker::widget([
                        'model' => $searchModel,
                        'attribute' => "inputValue[{$columnName}]",
                        'dateFormat' => Imei::DATE_PICKER_FORMAT,
                        'options' => [
                            'placeholder' => Yii::t('frontend', 'Enter Date From'),
                            'class' => 'form-control inputValue',
                            'autocomplete' => 'off',
                            'name' => "inputValue[{$columnName}]",
                        ],
                    ]);
                ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>