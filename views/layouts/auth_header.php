<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>

<div id="menu" class="container-fluid">
    <div class="container">

        <a href="/" id="logo" class="hidden-xs"><?= Html::img('@web/img/logo.png') ?></a>
        <a href="/" id="logo" class="visible-xs"><?= Html::img('@web/img/logo1.png') ?>
        <ul class="main-menu">
            <a href="/court"><li><?= Html::img('@web/img/field2.png', ['class' => 'visible-xs']) ?><span>Площадки</span></li></a>
            <a href="#"><li><?= Html::img('@web/img/ball2.png', ['class' => 'visible-xs']) ?><span>Игры</span></li></a>
            <div id="responsive-menu2">
                <ul class="dropdown">
                    <?= Html::img('@web/img/man-profile.png', ['class' => 'imgProfile']) ?>
                    <!-- <a href="#" class="dropdown-top hidden-xs">SuperBro</a> -->
                    <ul class="dropdown-inside">
                        <a class="newLetters" href="#">Оповещения</a>
                        <a class="setting" href="user/settings/profile">Настройки</a>
                        <!-- <img src="../img/mail.png"> -->
                        <a class="exit" href="/logout" data-method="POST">Выход</a>
                    </ul>
                    <?= Html::img('@web/img/polygon.png', ['class' => 'imgPolygon']) ?>
                </ul>
            </div>
        </ul>
    </div>
</div>