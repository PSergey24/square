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
<html lang="ru">
    <head>
        <meta charset="UTF-8"/>
        <?= Html::csrfMetaTags() ?>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
        <?php $this->beginBody() ?>
            <!--Header-->
            <?php if (Url::current() == '/site/index'): ?>
                <?= $this->render('header/main_header') ?>
            <?php else:
                    if (Yii::$app->user->isGuest) :?>
                <?= $this->render('header/guest_header', []) ?>
            <?php else:?>
                <?= $this->render('header/auth_header', []) ?>
            <?php endif ?>
                <?php endif ?>
            <!--<Header END-->

            <?= $content ?>

            <!--Footer-->
            <div class="container-fluid footer helper">
                    <a href="#">Обратная связь</a>
                    <a href="#">О компании</a>
                    <a href="#">Пользовательское соглашение</a>
                    <p>Квадрат 2016</p>
            </div>
            <div class="container-fluid footer">
                <a href="#">Обратная связь</a>
                <a href="#">О компании</a>
                <a href="#">Пользовательское соглашение</a>
                <p><a href="/">Квадрат 2016-2017</a></p>
            </div>
            <!--Footer END-->
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>