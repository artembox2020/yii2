<script>
    var numberOfDays = <?= $numberOfDays ?>;
    var summaryJournal = document.querySelector('.summary-journal');
</script>

<?php
    // auxiliary js funcions
    echo Yii::$app->view->render('/summary-journal/data/script-common', []);

    // renders main script builder
    echo Yii::$app->view->render(
        '/summary-journal/data/script-builder',
        [
            'lastYearIncome' => $lastYearIncome,
            'isDetailed' => $isDetailed,
            'lastMonthIncome' => !empty($lastMonthIncome) ? $lastMonthIncome : 0,
            'expandIncomes' => Yii::t('summaryJournal', 'Expand Incomes'),
            'wrapIncomes' => Yii::t('summaryJournal', 'Wrap Incomes'),
            'expandIdles' => Yii::t('summaryJournal', 'Expand Idles'),
            'wrapIdles' => Yii::t('summaryJournal', 'Wrap Idles')
        ]
    );
?>

<?php

    // check privilleges for summary journal
    $hasUserIncomesPermission = Yii::$app->user->can('summary-journal/incomes');
    $hasUserIdlesPermission = Yii::$app->user->can('summary-journal/idle');

    if ($hasUserIncomesPermission && $hasUserIdlesPermission) {
        echo "<script>Builder.makeJournalByAll();</script>";
    } elseif ($hasUserIncomesPermission) {
        echo "<script>Builder.makeJournalByIncomes();</script>";
    } elseif ($hasUserIdlesPermission) {
        echo "<script>Builder.makeJournalByIdles();</script>";
    } else {
        echo "<script>Builder.eraseAll();</script>";
        \Yii::$app->getSession()->setFlash('AccessDenied', 'Access denied');
        echo \Yii::$app->view->render('@app/modules/account/views/denied/access-denied');
    }
?>
