<?php

use yii\helpers\Html;

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
                <?= Html::input("text",  "inputValue[{$columnName}]", 
                        $params['inputValue'][$columnName],
                        [
                            'class' => 'form-control inputValue',
                            'placeholder' => Yii::t('frontend', 'Value')
                        ]
                    )
                ?>
            </div>
        </div>    
    </div>
</div>