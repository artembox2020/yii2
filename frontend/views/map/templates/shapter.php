<?php

/* @var $action int */

?>
<div class="monitoring-new map-shapter">
    <div class="container-fluid">
        <div class="mx-1 mx-md-5">
            <div class="tab-navs">
                <a class="tab-nav_item <?= Yii::$app->headerBuilder->getMapShapterTabActivity($action, ['index', 'cardofcard']) ?>"
                    id="general"
                    href="/map"
                >
                    <?= Yii::t('map', 'Cards') ?>
                </a>
                <a class="tab-nav_item <?= Yii::$app->headerBuilder->getMapShapterTabActivity($action, ['user', 'userscard']) ?>" 
                    id="technical"
                    href="/map/user"
                >
                    <?= Yii::t('map', 'Users') ?>
                </a>
            </div>
        </div>
    </div>
</div>