<?php

use common\widgets\phrase\PhraseListWidget;
use common\widgets\phrase\PhraseWidget;

echo PhraseWidget::widget();
echo PhraseListWidget::widget();
PhraseWidget::updateContainerOnSubmit($this,"#phrases");
?>