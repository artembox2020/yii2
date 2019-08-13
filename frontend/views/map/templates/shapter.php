<div class="monitoring-new map-shapter">
    <div class="container-fluid">
        <div class="mx-1 mx-md-5">
            <div class="tab-navs">
                <a class="tab-nav_item <?= Yii::$app->headerBuilder->getMapShapterTabActivity($action, 'index') ?>"
                    id="general"
                    href="/map"
                >
                    <?= Yii::t('map', 'Cards') ?>
                </a>
                <a class="tab-nav_item <?= Yii::$app->headerBuilder->getMapShapterTabActivity($action, 'user') ?>" 
                    id="technical"
                    href="/map/user"
                >
                    <?= Yii::t('map', 'Users') ?>
                </a>
            </div>
        </div>
    </div>
</div>