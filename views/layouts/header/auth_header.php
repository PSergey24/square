<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Profile;

$this->params['picture_href'] = Profile::getAvatar();
?>

<div id="menu" class="container-fluid">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 forSmall">
            <a href="/court" id="logo" class="hidden-xs"><?= Html::img('@web/img/logo.png') ?></a>
            <a href="/court" id="logo" class="visible-xs"><?= Html::img('@web/img/logo1.png') ?>
            <ul class="main-menu">
                <a href="/court"><li id="court"><i class="fa fa-map visible-xs fa-2x" aria-hidden="true"></i><span>Площадки</span></li></a>
                <a href="/game"><li id="game"><i class="fa fa-futbol-o visible-xs fa-2x" aria-hidden="true"></i><span>Игры</span></li></a>
                <div class="squareTop hidden-xs"><span>Не нашел площадку?</span><a href="/court/create">Добавь свою</a></div>
                <div id="responsive-menu2">
                    <ul class="dropdown">
                        <a href="/profile"><img class="imgProfile" src="<?= $this->params['picture_href'] ?>"></a>
                        <!-- <a href="#" class="dropdown-top hidden-xs">SuperBro</a> -->
                        <ul class="dropdown-inside">
                            <a class="setting" href="/profile"><i class="fa fa-user-o menu-icon" aria-hidden="true"></i>Профиль</a>
                            <a class="newLetters" href="#"><i class="fa fa-bell menu-icon" aria-hidden="true"></i>Оповещения</a>
                            <a class="setting" href="/settings/profile"><i class="fa fa-cog menu-icon" aria-hidden="true"></i>Настройки</a>
                            <div class="divide"></div>
                            <a class="exit" href="/logout" data-method="POST"><i class="fa fa-sign-out menu-icon" aria-hidden="true"></i>Выход</a>
                        </ul>
                        <i class="fa fa-caret-down imgPolygon fa-1x" aria-hidden="true"></i>
                    </ul>
                </div>
            </ul>
        </div>
</div>