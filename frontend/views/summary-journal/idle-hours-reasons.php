<br>
<?= Yii::t('summaryJournal', 'Idle Reasons Hours') ?><br><br>

<?php
if (($reason=explode("**", $idleHoursReasons)[2])) {
    echo Yii::t('summaryJournal', 'Idle Connect Central Board Hours').': '.round($reason/$all, 2).'<br>';
}

?>

<?php
if (($reason=explode("**", $idleHoursReasons)[3])) {
    echo Yii::t('summaryJournal', 'Idle Central Board Hours').': '.$reason.'<br>';
}
?>

<?php
if (($reason=explode("**", $idleHoursReasons)[0])) {
    echo Yii::t('summaryJournal', 'Idle Work Hours').': '.$reason.'<br>';
}

?>

<?php
if (($reason=explode("**", $idleHoursReasons)[1])) {
    echo Yii::t('summaryJournal', 'Idle Connect Hours').': '.$reason.'<br>';
}

?>