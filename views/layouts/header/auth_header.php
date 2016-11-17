<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>

<div id="menu" class="container-fluid">
    <div class="container">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 forSmall">
            <a href="/court" id="logo" class="hidden-xs"><?= Html::img('@web/img/logo.png') ?></a>
            <a href="/court" id="logo" class="visible-xs"><?= Html::img('@web/img/logo1.png') ?>
            <ul class="main-menu">
                <a href="/court"><li><?= Html::img('@web/img/field2.png', ['class' => 'visible-xs']) ?><span>Площадки</span></li></a>
                <a href="#"><li><?= Html::img('@web/img/ball2.png', ['class' => 'visible-xs']) ?><span>Игры</span></li></a>
                <div id="responsive-menu2">
                    <ul class="dropdown">
                        <a href="/profile"><img class="imgProfile" src="<?= $this->params['picture_href'] ?>"></a>
                        <!-- <a href="#" class="dropdown-top hidden-xs">SuperBro</a> -->
                        <ul class="dropdown-inside">
                            <a class="setting" href="/profile"><i class="fa fa-user fa-lg menu-icon" aria-hidden="true"></i>Профиль</a>
                            <a class="newLetters" href="#"><i class="fa fa-bell fa-lg menu-icon" aria-hidden="true"></i>Оповещения</a>
                            <a class="setting" href="/settings/profile"><i class="fa fa-cog fa-lg menu-icon" aria-hidden="true"></i>Настройки</a>
                            <div class="divide"></div>
                            <a class="exit" href="/logout" data-method="POST"><i class="fa fa-sign-out fa-lg menu-icon" aria-hidden="true"></i>Выход</a>
                        </ul>
                        <?= Html::img('@web/img/polygon.png', ['class' => 'imgPolygon']) ?>
                    </ul>
                </div>
            </ul>
        </div>
    </div>
</div>