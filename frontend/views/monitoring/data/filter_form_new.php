<?php

    use yii\helpers\Html;
    use \yii\jui\AutoComplete;

    echo Html::beginForm('', 'get', ['class' => 'monitoring-filter-form form-inline', 'data-pjax' => 0]);
?>
<div class="container-fluid">
    <div class="mx-1 mx-md-5">
        <div class="tab-navs">
            <a class="tab-nav_item active" id="general"><?= Yii::t('frontend', 'All') ?></a>
            <a class="tab-nav_item" id="technical"><?= Yii::t('frontend', 'Technical Data') ?></a>
            <a class="tab-nav_item" id="finance"><?= Yii::t('frontend', 'Financial Data') ?></a>
        </div>
        <div class="text-left search d-inline-block">        
            <?= AutoComplete::widget([
                'name' => 'address',
                'options' => [
                    'class' => 'form-control',
                    'style'  => "background: url(".Yii::getAlias('@storageUrl/main-new')."/img/search-icon.svg) no-repeat;".
                                "background-position: 10px 10px; background-size: 20px 20px;",
                    'placeholder' => Yii::t('frontend', 'Search by address')
                ],
                'value' => $params['address'],
                'clientOptions' => [
                    'source' => $addresses,
                    'autoFill' => false,
                ],
            ]);
            ?>
            <?= Html::dropDownList(
                'sortOrder', 
                $params['sortOrder'],
                $sortOrders,
                [
                    'class' => 'form-control',
                    'width' => '40'
                ]
            );
            ?>
        </div>
    </div>
    </div>

<?php
    echo Html::endForm();
?>