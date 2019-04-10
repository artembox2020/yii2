<div class='popup-block' style = "<?= $blockStyle ?>">
    <?php foreach ($imgSrcs as $imgSrc): ?>
        <?= \yii\helpers\Html::img($imgSrc) ?>
    <?php endforeach; ?>
    <div class='label' style = "<?= $labelStyle ?>"><?= $text ?></div>
</div>