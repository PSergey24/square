<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Court */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Courts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<link href="/css/squareProfile.css" rel="stylesheet">


<div class="container-fluid top">
    <div class="container s">

        <h2 class="h2-white">Курляндская ул. 10</h2>
        <p><a href="#" class="tag">Футбол</a><a href="#" class="tag">Баскетбол</a></p>
        <div class="description ">
            <a href="javascript:collapsElement('description-text')" title="" rel="nofollow" class="link">Описание площадки</a><?= Html::img('@web/img/down.png', ['class' => 'arrow', 'id' => '1']) ?>
            <div class="description-text" id="identifikator" style="display: none">
                <p>Классная площадка, с искусстенным газоном. Есть хорошие баскетбольные кольца. Так же есть 2 беговые дорожки. Ворота без сетки, но игре это особо не мешает.</p>
            </div>
        </div>
        <div class="buttons">
            <button class="mid-green-btn shadow"><?= Html::img('@web/img/star.png', ['class' => 'visible-xs-inline-block img']) ?><span class="hidden-xs">Добавить в избранные</span></button>
            <button class="mid-blue-btn shadow"><?= Html::img('@web/img/heart.png', ['class' => 'visible-xs-inline-block img']) ?><span class="hidden-xs">Мне нравится</span> <span class="players">15</span></button>
        </div>

    </div>
</div>



<div class="container">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 ">
        <div class="col-lg-12 col-xs-12 col-md-12 col-sm-12 box chat" id="map">

        </div>
    </div>
    <div class="col-lg-offset-1 col-lg-4 col-md-offset-1 col-md-4 col-sm-6 col-xs-12">
        <h2 class="h2-box">Ближайшие игры</h2>
        <div class="col-lg-12 col-xs-12 box games">
            <div class="game"><div class="time">Сегодня 18.45</div><?= Html::img('@web/img/ball-ok.png') ?><button class="mid-blue-btn">+ <span class="players">9</span></button></div>
            <div class="game"><div class="time">Завтра 19.00</div><img src="img/ball-not.png"><button class="mid-blue-btn">+ <span class="players">3</span></button></div>
            <button class="mid-green-btn" data-toggle="modal" data-target=".bs-example-modal-lg">Создать игру</button>
        </div>
    </div>
</div>

<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm ">
        <div class="modal-content game-create" class="create-game ">
            <p class="h2-black">Создание игры</p>
            <form>
                <p>
                <p class="little">Выберите вид спорта</p>
                <select name="sport" class="input sport">
                    <option value="1">Футбол</option>
                    <option value="2">Баскетбол</option>
                </select>
                </p>
                <p class="little">Выберите время игры</p>
                <p class="align-right">
                    <select name="day" class="input date">
                        <option value="1">Сегодня</option>
                        <option value="2">Завтра</option>
                    </select>
                    <input type="time" class="input date">
                </p>
                <p class="ball">
                    <span class="need">Нужен мяч</span> <input type="checkbox" name="ball" value="1"
                </p>
                <p class="center"><input type="submit" value="Готово" class="mid-green-btn"></p>
            </form>


        </div>
    </div>
</div>
<script>
    function collapsElement(id) {
        if ( document.getElementsByClassName(id)[0].style.display != "none" ) {
            document.getElementsByClassName(id)[0].style.display = 'none';
            document.getElementsByClassName('link')[0].style.color = 'rgba(255,255,255,0.7)';
            document.getElementById('1').src = 'images/down.png';
            document.getElementById('1').style.opacity = '0.7';
        }
        else {
            document.getElementsByClassName(id)[0].style.display = '';
            document.getElementsByClassName('link')[0].style.color = 'rgba(255,255,255,1)';
            document.getElementById('1').src = 'images/up.png';
            document.getElementById('1').style.opacity = '1';
        }
    }
</script>