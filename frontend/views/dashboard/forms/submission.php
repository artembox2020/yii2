<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $action string */
/* @var $start int */
/* @var $selector string */
/* @var $end int */
/* @var $active string */
/* @var $other string|null */

?>
<?php
    echo Html::beginForm($action, 'post', ['class' => 'form-dashboard-ajax', 'data-pjax' => '']);
?>

<input type="hidden" name="action" value="<?= $action ?>"/>
<input type="hidden" name="start" value="<?= $start ?>" />
<input type="hidden" name="selector" value="<?= $selector ?>" />
<input type="hidden" name="end" value="<?= $end ?>"/>
<input type="hidden" name="active" value="<?= $active ?>" />
<input type="hidden" name = "other" value= "<?= $other ?? null ?>" />
<button type="submit"></button>

<?php
    echo Html::endForm();
?>