<?php
use yii\helpers\Html;
use yii\helpers\Url;


/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */

?>
    <!--    <h2>This message allows you to visit our site home page by one click</h2>-->
<?//= Html::a('Go to home page', Url::home('http')) ?>
    Вы приглашены в компанию <br>
    Инвайт: <?= $textBody[1]; ?><br>
    Ваш пароль: <?= $textBody[0]; ?>
