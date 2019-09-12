<a class="add-card">
    <span class="add-card-label">
        <span class="label-add-card"><?= Yii::t('map', 'Add Card') ?></span>
        <span class="glyphicon glyphicon-plus hidden"></span>
    </span>
    <?= Yii::$app->view->render(
        '@frontend/views/map/templates/card_confirmation',
        [
            'userId' => Yii::$app->user->id
        ]
    )
    ?>
</a>