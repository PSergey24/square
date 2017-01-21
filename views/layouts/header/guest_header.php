<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>

<div id="menu" class="container-fluid">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 forSmall">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#responsive-menu">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="/" id="logo" class="hidden-xs"><?= Html::img('@web/img/logo.png') ?></a>
                <a href="/" id="logo" class="visible-xs"><?= Html::img('@web/img/logo1.png') ?></a>
                <ul class="main-menu">
                    <a href="/court">
                        <li id="court">
                            <?= Html::img('@web/img/field2.png', ['class' => 'visible-xs']) ?><span>Площадки</span>
                        </li>
                    </a>
                    <a href="/game">
                        <li id="game"><?= Html::img('@web/img/ball2.png', ['class' => 'visible-xs']) ?>
                            <span>Игры</span>
                        </li>
                    </a>
                </ul>
                <div class="collapse" id="responsive-menu">

                    <?= Html::a('Регистрация', Url::to(['/login', 'action' => 'register']),[
                        'class' => 'blue-big-button',
                        'id' => 'registration',
                    ])
                    ?>
                    <?= Html::a('Вход', Url::to(['/login', 'action' => 'login']),[
                        'id' => 'enter',
                    ])
                    ?>
                </div>
            </div>
        </div>
