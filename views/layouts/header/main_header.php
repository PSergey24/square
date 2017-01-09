<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$model = $this->params['model'];
$districts = $this->params['districts'];
$sport_types = $this->params['sport_types'];

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
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#responsive-menu">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="/" id="logo" class="hidden-xs"><?= Html::img('@web/img/logo.png') ?>
                <a href="/" id="logo" class="visible-xs"><?= Html::img('@web/img/logo1.png') ?></a>
                <ul class="main-menu">
                    <a href="/court"><li><?= Html::img('@web/img/field2.png', ['class' => 'visible-xs']) ?><span>Площадки</span></li></a>
                    <a href="/game"><li><?= Html::img('@web/img/ball2.png', ['class' => 'visible-xs']) ?><span>Игры</span></li></a>
                </ul>
                <div class="collapse" id="responsive-menu">
                    <?= Html::a('Регистрация', Url::to(['/login']),[
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
    </div>
    <h1 class="h1-white big"><span class="hello">Привет!</span> Я - Квадрат :)</h1>
    <p class="text">С моей помощью ты можешь организовать<br>игру или тренировкуна любой площадке своего города</p>

    <div class="container">

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12  forSmall">

            <?php $form = ActiveForm::begin([
                'layout' => 'inline',
                'action' => Url::to(['/court']),
            ]);
            ?>

            <?= $form->field($model, 'city')
                ->dropDownList(['Санкт-Петербург'], [
                    'class' => 'search selectpicker',
                    'id' => 'city_type'
                ])->label(false);
            ?>

            <?= $form->field($model, 'district_sity')
                ->dropDownList($districts, [
                    'id' => 'district_type',
                    'class' => 'search selectpicker',
                    'prompt' => 'Выберите район'
                ])
                ->label(false);
            ?>

            <?= $form->field($model, 'sport_type')
                ->dropDownList($sport_types, [
                    'class' => 'search selectpicker',
                    'prompt' => 'Вид спорта'
                ])
                ->label(false);
            ?>

            <?= Html::submitButton('Поиск', [
                    'class' => 'mid-green-btn search',
                    'id' => 'submit',
                ]) ?>

        <?php ActiveForm::end(); ?>

            <form id="form">
                <select id="city_type" class="search">
                    <option selected>Санкт-Петербург</option>
                </select>
                <select id="district_type" class="search">
                    <option value="0" selected style="display:none;">Выберите район</option>
                    <option value="19">Выбрать все районы</option>
                    <option value="1">Кронштадтский</option>
                    <option value="2">Адмиралтейский</option>
                    <option value="3">Василеостровский</option>
                    <option value="4">Выборгский</option>
                    <option value="5">Калининский</option>
                    <option value="6">Кировский</option>
                    <option value="7">Колпинский</option>
                    <option value="8">Красногвардейский</option>
                    <option value="9">Красносельский</option>
                    <option value="10">Курортный</option>
                    <option value="11">Московский</option>
                    <option value="12">Невский</option>
                    <option value="13">Петроградский</option>
                    <option value="14">Петродворцовый</option>
                    <option value="15">Приморский</option>
                    <option value="16">Пушкинский</option>
                    <option value="17">Фрунзенский</option>
                    <option value="18">Центральный</option>
                </select>
                <select id="sport_type" class="search">
                    <option value="0" selected style="display:none;">Вид спорта</option>
                    <option value="3">Выбрать все виды спорта</option>
                    <option value="1">Баскетбол</option>
                    <option value="2">Футбол</option>
                </select>

                <input id="submit" type="submit" value="Поиск" class="mid-green-btn search">
                <a href="#mid" class="hidden-xs"><i class="fa fa-angle-down fa-3x" aria-hidden="true"></i></a>
            </form>
        </div>
    </div>
</div>
