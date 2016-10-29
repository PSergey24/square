<?php
use yii\helpers\Html;

?>

<div  class="container-fluid top">
    <div class="row" id="menu">
        <div class="container">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#responsive-menu">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="/" id="logo" class="hidden-xs"><?= Html::img('@web/img/logo.png') ?>
                <a href="/" id="logo" class="visible-xs"><?= Html::img('@web/img/logo1.png') ?></a>
                <ul class="main-menu">
                    <a href="/court"><li><?= Html::img('@web/img/field2.png', ['class' => 'visible-xs']) ?><span>Площадки</span></li></a>
                    <a href="#"><li><?= Html::img('@web/img/ball2.png', ['class' => 'visible-xs']) ?><span>Игры</span></li></a>
                </ul>
                <div class="collapse" id="responsive-menu">
                    <a href="/login" id="registration" class="blue-big-button">Регистрация</a>
                    <a href="/login" id="enter">Вход</a>
                </div>
            </div>
        </div>
    </div>
    <h1 class="h1-white big"><span class="hello">Привет!</span> Я - Квадрат :)</h1>
    <p class="text">С моей помощью ты можешь организовать<br>игру или тренировкуна любой площадке своего города</p>
    <form>
        <select id="sport_type" class="input">
            <option value="0">Вид спорта</option>
            <option value="1">Баскетбол</option>
            <option value="2">Футбол</option>
        </select>
        <select class="input">
            <option selected>Санкт-Петербург</option>
        </select>
        <input id="submit" type="button" value="Поиск" class="mid-green-btn">
    </form>
    <a href="#mid"><?= Html::img('@web/img/arrow.png', ['class' => 'arrow']) ?><a>
</div>
