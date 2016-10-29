<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\assets\AppAsset;

AppAsset::register($this);

/* @var $this yii\web\View */
/* @var $content string */
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <?= Html::csrfMetaTags() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= Html::encode($this->title) ?></title>
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.2.3.js"></script>
    
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
<!--    <header>-->
    <?php
        if (Url::current() == '/site/index'): ?>
        <?= $this->render('main_header') ?>
    <?php else:
            if (Yii::$app->user->isGuest) :?>
        <?= $this->render('guest_header', []) ?>
    <?php else:?>
        <?= $this->render('auth_header', []) ?>
    <?php endif ?>
        <?php endif ?>

    <!--    </header>-->

    <?= $content ?>
    <div class="helper"></div>
    <div id="footer" class="container-fluid footer">
        <a href="#">Обратная связь</a>
        <a href="#">О компании</a>
        <a href="#">Пользовательское соглашение</a>
        <p>Квадрат 2016</p>
    </div>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDxkhFJ3y--2AadULGUoE9kdlRH3nT-668&signed_in=false&callback=initMap&sensor=true_or_false"  async defer></script>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>