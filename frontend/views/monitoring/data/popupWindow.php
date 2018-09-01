<div class='popup-block'>
    <?php foreach ($imgSrcs as $imgSrc): ?>
        <?= \yii\helpers\Html::img($imgSrc) ?>
    <?php endforeach; ?>
    <div class='label'><?= $text ?></div>
</div>