<?php
use yii\helpers\Html;

$this->registerJs("

    $('#form').on('click','a', function (event) {
        event.preventDefault();
        var id  = $(this).attr('href'),
            top = $(id).offset().top;
        $('body,html').animate({scrollTop: top}, 300);
    });

");
?>

<div  class="container-fluid top">
    <div class="row" id="menu">
        <div class="container">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <a href="/" id="logo" class="hidden-xs"><?= Html::img('@web/img/logo.png') ?>
                <a href="/" id="logo" class="visible-xs"><?= Html::img('@web/img/logo1.png') ?></a>
                <a href="/login" id="registration" class="blue-big-button">Регистрация</a>
                    <a href="/login" id="enter">Вход</a>
                <ul class="main-menu">
                    <a href="/court"><li><?= Html::img('@web/img/field2.png', ['class' => 'visible-xs']) ?><span>Площадки</span></li></a>
                    <a href="#"><li><?= Html::img('@web/img/ball2.png', ['class' => 'visible-xs']) ?><span>Игры</span></li></a>
                </ul>
            </div>
        </div>
    </div>
    <h1 class="h1-white big"><span class="hello">Привет!</span> Я - Квадрат :)</h1>
    <p class="text">С моей помощью ты можешь организовать<br>игру или тренировкуна любой площадке своего города</p>
    <form id="form">
        <select id="city_type" class="search">
            <option selected>Санкт-Петербург</option>
        </select>
        <select id="district_type" class="search">
            <option value="0" selected style="display:none;">Выберите район</option>
            <option value="1">Выбрать все районы</option>
            <option value="2">Адмиралтейский</option>
            <option value="3">Кировский</option>
            <option value="4">Центральный</option>
        </select>
        <select id="sport_type" class="search">
            <option value="0" selected style="display:none;">Вид спорта</option>
            <option value="3">Выбрать все виды спорта</option>
            <option value="1">Баскетбол</option>
            <option value="2">Футбол</option>
        </select>
        
        <input id="submit" type="button" value="Поиск" class="mid-green-btn search">
        <a href="#mid" class="hidden-xs"><i class="fa fa-angle-down fa-3x" aria-hidden="true"></i></a>
    </form>

</div>
