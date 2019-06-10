<?php

use frontend\services\globals\EntityHelper;

/* @var $date string */
/* @var $logTitle string */
/* @var $logTitleDate  string */

?>
<div class="jlog-item">
<?= $date ?>    
<?= EntityHelper::makePopupWindow(
        [],
        $logTitle.': '.$logTitleDate,
        'left: 30px'
    )
?>
</div>