<script>
    (function() 
    {
        var monitoring = document.querySelector('.monitoring');

        adjustTableSizeTheSameHeight('terminal', 'cell-bill-acceptance', 0);
        adjustTableSizeTheSameHeight('terminal', 'cell-software', 0);
        adjustTableSizeTheSameHeight('terminal', 'cell-actions', 0);
        adjustTableSizeTheSameHeight('terminal', 'cell-modem', 0);

        // cell action script
        <?= Yii::$app->view->render('/monitoring/data/cell_actions_handler') ?>

        // adjust table main script
        <?= Yii::$app->view->render('/monitoring/data/adjust_table') ?>
    }());
</script>