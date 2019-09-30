<?php

use common\models\User;
use frontend\services\custom\Debugger;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAssetNew;
use frontend\models\NavItem;
use frontend\services\globals\Entity;
use lo\modules\noty\Wrapper;

/* @var $this \yii\web\View */
/* @var $content string */

AppAssetNew::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <link rel="shortcut icon" href="<?= Yii::getAlias('@storageUrl/main-new') ?>/img/favicon.ico" type="image/x-icon">
        <?php $this->head() ?>
    </head>
    <body>
        <?php $this->beginBody() ?>

        <div class="header-container-mn">
            <?= Yii::$app->headerBuilder->makeHeader('main-new') ?>
        </div>

        <div class="wrap">
            <div class="container width100">
                <div></div>
                <?= Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>
                <?php Wrapper::widget(); ?>
                <?= $content ?>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <script src="<?= Yii::getAlias('@web/static') ?>/js/monitoringdata/main.js"></script>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
