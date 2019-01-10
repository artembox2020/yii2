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

<script>
    // main journal
    Builder.makeJournal();

    // clones journal
    Builder.cloneJournal();

    // cloned journal table
    var summaryJournal = document.querySelector('.summary-journal-clone');
    Builder.makeJournalClone();

</script>